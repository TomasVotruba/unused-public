<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())->ignoreErrorsOnPaths([__DIR__ . '/tests'], [ErrorType::UNKNOWN_CLASS]);
