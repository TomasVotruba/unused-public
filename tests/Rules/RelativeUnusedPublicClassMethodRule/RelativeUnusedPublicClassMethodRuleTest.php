<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\RelativeUnusedPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\Callable_\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\Callable_\CallbackFunctionCollector;
use TomasVotruba\UnusedPublic\Collectors\FormTypeClassCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Rules\RelativeUnusedPublicClassMethodRule;

final class RelativeUnusedPublicClassMethodRuleTest extends RuleTestCase
{
    #[DataProvider('provideData')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideData(): Iterator
    {
        $firstErrorMessage = sprintf(RelativeUnusedPublicClassMethodRule::ERROR_MESSAGE, 100, 55);
        $secondErrorMessage = sprintf(RelativeUnusedPublicClassMethodRule::ERROR_MESSAGE, 100, 55);

        yield [[__DIR__ . '/Fixture/SomeUnusedPublicMethod.php'], [
            [$firstErrorMessage, 7],
            [$secondErrorMessage, 11],
        ]];

        yield [[__DIR__ . '/Fixture/HalfUsedMethods.php'], []];
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
            self::getContainer()->getByType(FormTypeClassCollector::class),
            // method call
            self::getContainer()->getByType(MethodCallCollector::class),
            self::getContainer()->getByType(MethodCallableCollector::class),
            // static call
            self::getContainer()->getByType(StaticMethodCallCollector::class),
            // callables
            self::getContainer()->getByType(StaticMethodCallableCollector::class),
            self::getContainer()->getByType(AttributeCallableCollector::class),
            self::getContainer()->getByType(CallbackFunctionCollector::class),
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(RelativeUnusedPublicClassMethodRule::class);
    }
}
