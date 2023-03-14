<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $ecsConfig->skip([
        PhpUnitTestAnnotationFixer::class => 'tests/Rules/UnusedPublicClassMethodRule/Fixture/SkipTestCaseAnnotationMethod.php'
    ]);

    $ecsConfig->sets([
        SetList::COMMON,
        SetList::PSR_12,
        SetList::CLEAN_CODE,
        SetList::SYMPLIFY,
    ]);
};
