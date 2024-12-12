<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\Callable_\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicClassMethodRule;

final class TemplatePathsTest extends RuleTestCase
{
    /**
     * @param string[] $filePaths
     * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideDataWithTwigTemplates')]
    #[DataProvider('provideDataWithBladeTemplates')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideDataWithTwigTemplates(): Iterator
    {
        yield [[__DIR__ . '/Fixture/SkipTwigEntityWithMethods.php'], []];

        // this method is required by parent contract, and should be skipped
        yield [[
            __DIR__ . '/Fixture/SkipParentInterfaceRequired.php',
            __DIR__ . '/Source/Twig/ExistingTwigExtension.php',
        ], []];
    }

    public static function provideDataWithBladeTemplates(): Iterator
    {
        yield [[__DIR__ . '/Fixture/Blade/SkipUsedInBlade.php'], []];
        yield [[__DIR__ . '/Fixture/Blade/SkipStaticCallUsedInBlade.php'], []];
        yield [[__DIR__ . '/Fixture/Blade/SkipTag.php'], []];
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
        return self::getContainer()->getByType(UnusedPublicClassMethodRule::class);
    }
}
