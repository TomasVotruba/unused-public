<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\LocalOnlyPublicClassMethodRule;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\CaseInsensitiveMethodName;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedEnumMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedPublicMethod;

final class LocalOnlyPublicClassMethodRuleTest extends RuleTestCase
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
        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            LocallyUsedPublicMethod::class,
            'runHere'
        );
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicMethod.php'], [[$errorMessage, 9, RuleTips::NARROW_SCOPE]]];

        $errorMessage = sprintf(
            LocalOnlyPublicClassMethodRule::ERROR_MESSAGE,
            LocallyUsedEnumMethod::class,
            'runHere'
        );

        yield [[__DIR__ . '/Fixture/LocallyUsedEnumMethod.php'], [[$errorMessage, 12, RuleTips::NARROW_SCOPE]]];
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
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LocalOnlyPublicClassMethodRule::class);
    }
}
