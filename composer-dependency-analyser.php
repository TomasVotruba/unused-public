<?php declare(strict_types = 1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->ignoreErrorsOnPackage('nikic/php-parser', [ErrorType::DEV_DEPENDENCY_IN_PROD]) // prepared test tooling
    ->ignoreErrorsOnPaths([
        __DIR__ . '/tests',
    ], [ErrorType::UNKNOWN_CLASS]);
