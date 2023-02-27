<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicStaticPropertyFetchCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicPropertyRule;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocallyUsedStaticProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocalyUsedPublicProperty;

final class UnusedPublicPropertyRuleTest extends RuleTestCase
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
        $errorMessage = sprintf(UnusedPublicPropertyRule::ERROR_MESSAGE, LocalyUsedPublicProperty::class, 'name');
        yield [[__DIR__ . '/Fixture/LocalyUsedPublicProperty.php'],
            [[$errorMessage, 7, RuleTips::SOLUTION_MESSAGE]], ];

        yield [
            [__DIR__ . '/Fixture/LocalyUsedPublicProperty.php', __DIR__ . '/Source/UsingExternalProperty.php'],
            [],
        ];

        $errorMessage = sprintf(
            UnusedPublicPropertyRule::ERROR_MESSAGE,
            LocallyUsedStaticProperty::class,
            'somePublicStaticProperty'
        );
        yield [
            [__DIR__ . '/Fixture/LocallyUsedStaticProperty.php'],
            [[$errorMessage, 7, RuleTips::SOLUTION_MESSAGE]],
        ];

        yield [[
            __DIR__ . '/Fixture/AnotherClassUsingPublicStaticProperty.php',
            __DIR__ . '/Source/SkipExternallyUsedPublicStaticProperty.php',
        ], []];

        yield [[__DIR__ . '/Fixture/SkipPrivateProperty.php'], []];

        yield [
            [
                __DIR__ . '/Fixture/AbstractBaseclass.php',
                __DIR__ . '/Fixture/ExtendsAbstract.php',
                __DIR__ . '/Fixture/Provider.php',
            ],
            [],
        ];
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
            self::getContainer()->getByType(PublicPropertyCollector::class),
            self::getContainer()->getByType(PublicPropertyFetchCollector::class),
            self::getContainer()->getByType(PublicStaticPropertyFetchCollector::class),
        ];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();
        return $container->getByType(UnusedPublicPropertyRule::class);
    }
}
