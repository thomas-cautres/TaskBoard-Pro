<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;

class FeatureContext extends MinkContext implements Context
{
    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope): void
    {
        if (TestResult::FAILED !== $scope->getTestResult()->getResultCode()) {
            return;
        }

        $driver = $this->getSession()->getDriver();

        // Check if it's a Panther driver (supports screenshots)
        if (!$driver instanceof \Behat\Mink\Driver\PantherDriver) {
            return;
        }

        $screenshotDir = '/app/var/screenshots';

        if (!is_dir($screenshotDir)) {
            mkdir($screenshotDir, 0777, true);
        }

        $filename = sprintf(
            '%s_%s_%s.png',
            date('Y-m-d_H-i-s'),
            $scope->getFeature()->getTitle(),
            $scope->getStep()->getLine()
        );

        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);
        $filepath = $screenshotDir.'/'.$filename;

        try {
            $screenshot = $this->getSession()->getScreenshot();
            file_put_contents($filepath, $screenshot);
            echo "\n📸 Screenshot saved: {$filepath}\n";
        } catch (\Exception $e) {
            echo "\n❌ Could not take screenshot: {$e->getMessage()}\n";
        }

        // Also save HTML for debugging
        try {
            $htmlFile = str_replace('.png', '.html', $filepath);
            file_put_contents($htmlFile, $this->getSession()->getPage()->getHtml());
            echo "📄 HTML saved: {$htmlFile}\n";
        } catch (\Exception $e) {
            echo "❌ Could not save HTML: {$e->getMessage()}\n";
        }
    }

    /**
     * @When I wait :seconds second(s)
     */
    public function iWaitSeconds(int $seconds): void
    {
        sleep($seconds);
    }

    /**
     * @Then I dump the page
     */
    public function iDumpThePage(): void
    {
        $content = $this->getSession()->getPage()->getText();
        echo "\n========== PAGE CONTENT ==========\n";
        echo substr($content, 0, 500);
        echo "\n==================================\n";
    }
}
