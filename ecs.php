<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $ecsConfig->skip([
        PhpUnitTestAnnotationFixer::class => 'tests/Rules/UnusedPublicClassMethodRule/Fixture/SkipTestCaseAnnotationMethod.php',
        ProtectedToPrivateFixer::class => 'tests/Rules/ProtectedClassMethodInFinalClassRule/*'
    ]);

    $ecsConfig->sets([
        SetList::COMMON,
        SetList::PSR_12,
        SetList::CLEAN_CODE,
        SetList::SYMPLIFY,
    ]);
};
