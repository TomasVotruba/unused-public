<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\Callable_\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\Callable_\CallUserFuncCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\LocalOnlyPublicClassMethodRule;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\CaseInsensitiveMethodName;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedEnumMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedPublicMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\SkipInterfaceMethod;

final class LocalOnlyPublicClassMethodRuleTest extends RuleTestCase
{
    /**
     * @param string[] $filePaths
     * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideData(): Iterator
    {
        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            SkipInterfaceMethod::class,
            'publicFormatUrl'
        );
        yield [[__DIR__ . '/Fixture/SkipInterfaceMethod.php'], [[$errorMessage, 20, RuleTips::NARROW_SCOPE]]];

        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            LocallyUsedPublicMethod::class,
            'runHere'
        );

        yield [[__DIR__ . '/Fixture/LocallyUsedPublicMethod.php'], [[$errorMessage, 9, RuleTips::NARROW_SCOPE]]];

        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            CaseInsensitiveMethodName::class,
            'runHere'
        );
        yield [[__DIR__ . '/Fixture/CaseInsensitiveMethodName.php'], [[$errorMessage, 9, RuleTips::NARROW_SCOPE]]];

        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            LocallyUsedEnumMethod::class,
            'runHere'
        );
        yield [[__DIR__ . '/Fixture/LocallyUsedEnumMethod.php'], [[$errorMessage, 12, RuleTips::NARROW_SCOPE]]];
        yield [[__DIR__ . '/Fixture/SkipPublicCallbackMethod.php'], []];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    /**
     * @return array<Collector>
     */
    protected function getCollectors(): array
    {
        return [
            self::getContainer()->getByType(PublicClassMethodCollector::class),
            self::getContainer()->getByType(MethodCallCollector::class),
            self::getContainer()->getByType(StaticMethodCallCollector::class),
            self::getContainer()->getByType(AttributeCallableCollector::class),
            self::getContainer()->getByType(CallUserFuncCollector::class),
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LocalOnlyPublicClassMethodRule::class);
    }
}
