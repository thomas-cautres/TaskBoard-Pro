<?php

declare(strict_types=1);

namespace App\Tests\Behat;

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

    private function getContainer(): ContainerInterface
    {
        /** @var BrowserKitDriver $driver */
        $driver = $this->getSession()->getDriver();

        /** @var KernelBrowser $client */
        $client = $driver->getClient();

        return $client->getContainer();
    }
}
