<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withSkip([
        PhpUnitTestAnnotationFixer::class => 'tests/Rules/UnusedPublicClassMethodRule/Fixture/SkipTestCaseAnnotationMethod.php'
    ])
    ->withPreparedSets(common: true, psr12: true, cleanCode: true, symplify: true);
