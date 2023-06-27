<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\CallUserFuncCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\LocalOnlyPublicClassMethodRule;
use TomasVotruba\UnusedPublic\Rules\ProtectedMethodInFinalClassRule;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\CaseInsensitiveMethodName;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedEnumMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedPublicMethod;

final class ProtectedMethodInFinalClassRuleTest extends RuleTestCase
{
    /**
     * @param string[] $filePaths
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideData(): Iterator
    {
        yield [[__DIR__ . '/Fixture/SkipOverriddenProtectedMethodInFinalClass.php'], []];
        yield [[__DIR__ . '/Fixture/SkipNonFinalClass.php'], []];
        yield [[__DIR__ . '/Fixture/SkipProtectedApiMethodInFinalClass.php'], []];

        yield [
            [__DIR__ . '/Fixture/ProtectedMethodInFinalClass.php'],
            [
                [sprintf(ProtectedMethodInFinalClassRule::ERROR_MESSAGE, 'protectedMethod' ), 13],
                [sprintf(ProtectedMethodInFinalClassRule::ERROR_MESSAGE, 'staticProtectedMethod' ), 25]
            ]
        ];

    }


    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ProtectedMethodInFinalClassRule::class);
    }
}
