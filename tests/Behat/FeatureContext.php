<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Driver\PantherDriver;
use Behat\MinkExtension\Context\MinkContext;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\Panther\Client;

class FeatureContext extends MinkContext implements Context
{
    private const int RETRY_SLEEP = 10000;
    private const int RETRY_MAX_TIME = 10;

    private string $signedUrl;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly SessionFactoryInterface $sessionFactory,
    ) {
    }

    /**
     * @BeforeScenario @javascript
     */
    public function startSession(): void
    {
        $session = $this->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }
    }

    /**
     * @BeforeScenario
     */
    public function resetDatabase(): void
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();

        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new ProjectFixtures($this->userRepository));

        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @Given there is a signed confirmation URL for :email
     */
    public function thereIsASignedConfirmationUrlFor(string $email): void
    {
        $urlSigner = $this->getContainer()->get(UrlSignerInterface::class);
        $this->signedUrl = $urlSigner->sign("/confirm/{$email}", 3600);
    }

    /**
     * @When I visit the signed confirmation URL
     */
    public function iVisitTheSignedConfirmationUrl(): void
    {
        $this->visitPath($this->signedUrl);
    }

    /**
     * @When I visit a tampered confirmation URL
     */
    public function iVisitATamperedConfirmationUrl(): void
    {
        $this->visitPath($this->signedUrl.'&tampered=true');
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \Exception(sprintf('User with email "%s" not found', $email));
        }

        $driver = $this->getSession()->getDriver();
        if ($driver instanceof PantherDriver) {
            $this->visitPath('/');
            $token = new TestBrowserToken($user->getRoles(), $user, 'main');
            $session = $this->sessionFactory->createSession();
            $session->set('_security_main', serialize($token));
            $session->save();
            $this->getSession()->setCookie($session->getName(), $session->getId());
        } else {
            /** @var KernelBrowser $client */
            $client = $driver->getClient();
            $client->loginUser($user);
        }
    }

    /**
     * @Then the project :name should exist in database
     */
    public function theProjectShouldExistInDatabase(string $name): void
    {
        $projectRepository = $this->getContainer()->get(ProjectRepository::class);
        $project = $projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }
    }

    /**
     * @Then the project :name should have type :type
     */
    public function theProjectShouldHaveType(string $name, string $type): void
    {
        $projectRepository = $this->getContainer()->get(ProjectRepository::class);
        $project = $projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        if ($project->getType()->value !== $type) {
            throw new \Exception(sprintf('Project type is "%s", expected "%s"', $project->getType()->value, $type));
        }
    }

    /**
     * @Then the project :name should have :count columns
     */
    public function theProjectShouldHaveColumns(string $name, int $count): void
    {
        $projectRepository = $this->getContainer()->get(ProjectRepository::class);
        $project = $projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        $actualCount = $project->getColumns()->count();
        if ($actualCount !== $count) {
            throw new \Exception(sprintf('Project has %d columns, expected %d', $actualCount, $count));
        }
    }

    /**
     * @Then the project :name should have column :columnName at position :position
     */
    public function theProjectShouldHaveColumnAtPosition(string $name, string $columnName, int $position): void
    {
        $projectRepository = $this->getContainer()->get(ProjectRepository::class);
        $project = $projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        $column = $project->getColumns()->get($position);

        if (!$column) {
            throw new \Exception(sprintf('No column found at position %d', $position));
        }

        if ($column->getName() !== $columnName) {
            throw new \Exception(sprintf('Column at position %d is "%s", expected "%s"', $position, $column->getName(), $columnName));
        }
    }

    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event): void
    {
        if (99 !== $event->getTestResult()->getResultCode()) { // 99 = failed
            return;
        }

        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof PantherDriver) {
            return;
        }

        $screenshotDir = 'var/behat/screenshots';
        if (!is_dir($screenshotDir)) {
            mkdir($screenshotDir, 0777, true);
        }

        $filename = sprintf(
            '%s/%s_%s.png',
            $screenshotDir,
            date('Y-m-d_H-i-s'),
            uniqid()
        );

        file_put_contents($filename, $this->getSession()->getScreenshot());
        echo sprintf("Screenshot saved: %s\n", $filename);
    }

    /**
     * @When /^I wait (?P<num>\d+) ms$/
     */
    public function iWait(int $time): void
    {
        $this->getSession()->wait(
            $time,
        );
    }

    private function getContainer(): ContainerInterface
    {
        return $this->getClient()->getContainer();
    }

    private function getClient(): KernelBrowser|Client
    {
        /** @var BrowserKitDriver|PantherDriver $driver */
        $driver = $this->getSession()->getDriver();

        /** @var KernelBrowser|Client $client */
        $client = $driver->getClient();

        return $client;
    }

    private function retryStep(
        callable $step,
        int $maxTime = self::RETRY_MAX_TIME,
        int $sleep = self::RETRY_SLEEP,
    ): void {
        $startTime = \time();
        do {
            try {
                $step();
                \usleep(1000);

                return;
            } catch (\Exception $e) {
                \usleep($sleep);
                $ex = $e;
            }
        } while (\time() - $startTime <= $maxTime);

        throw $ex;
    }
}
