<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\PantherDriver;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;

class BaseContext extends MinkContext implements Context
{
    private const int RETRY_SLEEP = 10000;
    private const int RETRY_MAX_TIME = 10;

    private string $signedUrl;

    public function __construct(
        private readonly UrlSignerInterface $urlSigner,
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

    public function clickLink(mixed $link): void
    {
        $this->retryStep(function () use ($link) {
            parent::clickLink($link);
        });
    }

    public function pressButton(mixed $button): void
    {
        $this->retryStep(function () use ($button) {
            parent::pressButton($button);
        });
    }

    public function assertPageContainsText(mixed $text): void
    {
        $this->retryStep(function () use ($text) {
            parent::assertPageContainsText($text);
        });
    }

    public function assertPageAddress(mixed $page): void
    {
        $this->retryStep(function () use ($page) {
            parent::assertPageAddress($page);
        });
    }

    public function assertElementContainsText($element, $text): void
    {
        $this->retryStep(function () use ($element, $text) {
            parent::assertElementContainsText($element, $text);
        });
    }

    /**
     * @Given there is a signed confirmation URL for :email
     */
    public function thereIsASignedConfirmationUrlFor(string $email): void
    {
        $this->signedUrl = $this->urlSigner->sign("/confirm/{$email}", 3600);
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
            $this->visitPath('/app');
        } else {
            /** @var KernelBrowser $client */
            $client = $driver->getClient();
            $client->loginUser($user);
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

    public function fillField(mixed $field, mixed $value): void
    {
        $this->retryStep(function () use ($field, $value) {
            parent::fillField($field, $value);
            $element = $this->getSession()->getPage()->find('css', '#'.$field)?->getValue();

            if ('' === $element) {
                throw new ExpectationException('element empty', $this->getSession()->getDriver());
            }
        });
    }

    /**
     * @When /^I click the "([^"]*)" element$/
     */
    public function iClick(string $selector): void
    {
        $this->retryStep(function () use ($selector) {
            $page = $this->getSession()->getPage();
            $element = $page->find('css', $selector);

            if (null === $element) {
                throw new ExpectationException('element empty', $this->getSession()->getDriver());
            }

            $element->click();
        });
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
