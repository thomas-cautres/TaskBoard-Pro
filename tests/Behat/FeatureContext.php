<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\MinkExtension\Context\MinkContext;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FeatureContext extends MinkContext implements Context
{
    private string $signedUrl;

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
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \Exception(sprintf('User with email "%s" not found', $email));
        }

        $this->getClient()->loginUser($user);
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

    private function getContainer(): ContainerInterface
    {
        return $this->getClient()->getContainer();
    }

    private function getClient(): KernelBrowser
    {
        /** @var BrowserKitDriver $driver */
        $driver = $this->getSession()->getDriver();

        /** @var KernelBrowser $client */
        $client = $driver->getClient();

        return $client;
    }
}
