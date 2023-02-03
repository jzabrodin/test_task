<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR-12' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
