<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    'declare_strict_types' => true,
    'use_arrow_functions' => true,
    'is_null' => true,
])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
