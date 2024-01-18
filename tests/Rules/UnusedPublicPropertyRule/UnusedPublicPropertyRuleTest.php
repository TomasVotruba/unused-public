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
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\IgnoresPrivateApiProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocallyUsedStaticProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocallyUsedStaticPropertyViaStatic;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocalyUsedPublicProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UnusedInternalClassPublicProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UnusedPublicInternalProperty;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedInTestCaseOnly;

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

        $errorMessage = sprintf(
            UnusedPublicPropertyRule::ERROR_MESSAGE,
            LocallyUsedStaticPropertyViaStatic::class,
            'somePublicStaticProperty'
        );
        yield [
            [__DIR__ . '/Fixture/LocallyUsedStaticPropertyViaStatic.php'],
            [[$errorMessage, 7, RuleTips::SOLUTION_MESSAGE]],
        ];

        yield [[
            __DIR__ . '/Fixture/AnotherClassUsingPublicStaticProperty.php',
            __DIR__ . '/Source/SkipExternallyUsedPublicStaticProperty.php',
        ], []];

        yield [[__DIR__ . '/Fixture/SkipPrivateProperty.php'], []];

        // laravel
        yield [[__DIR__ . '/Fixture/SkipLaravelCommandSignatureProperty.php'], []];
        yield [[__DIR__ . '/Fixture/SkipLivewireComponent.php'], []];

        yield [
            [
                __DIR__ . '/Fixture/AbstractBaseclass.php',
                __DIR__ . '/Fixture/ExtendsAbstract.php',
                __DIR__ . '/Fixture/Provider.php',
            ],
            [],
        ];

        yield [[__DIR__ . '/Fixture/SkipInheritedPublicApiProperty.php'], []];
        yield [[__DIR__ . '/Fixture/SkipInheritedProtectedApiProperty.php'], []];
        yield [[__DIR__ . '/Fixture/SkipPublicApiProperty.php'], []];

        $errorMessage = sprintf(
            UnusedPublicPropertyRule::ERROR_MESSAGE,
            IgnoresPrivateApiProperty::class,
            'property'
        );
        yield [
            [__DIR__ . '/Fixture/IgnoresPrivateApiProperty.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]],
        ];

        $errorMessage1 = sprintf(UnusedPublicPropertyRule::ERROR_MESSAGE, UsedInTestCaseOnly::class, 'property');
        yield [
            [__DIR__ . '/Fixture/UsedInTestCaseOnly.php', __DIR__ . '/Source/TestCaseUser.php'],
            [[$errorMessage1, 7, RuleTips::SOLUTION_MESSAGE]],
        ];

        yield [[__DIR__ . '/Fixture/plain.php', __DIR__ . '/Source/PublicPropertyClass.php'], []];

        yield [
            [
                __DIR__ . '/Fixture/plain-static-and-nonstatic.php',
                __DIR__ . '/Source/PublicPropertyClass.php',
                __DIR__ . '/Source/PublicStaticPropertyClass.php',
            ],
            [],
        ];

        yield [
            [
                __DIR__ . '/Fixture/plain-superclass.php',
                __DIR__ . '/Source/PublicPropertyClass.php',
                __DIR__ . '/Source/PublicPropertySubclass.php',
            ],
            [],
        ];

        yield [
            [
                __DIR__ . '/Fixture/plain-superclass-static.php',
                __DIR__ . '/Source/PublicStaticPropertyClass.php',
                __DIR__ . '/Source/PublicStaticPropertySubclass.php',
            ],
            [],
        ];

        yield [
            [
                __DIR__ . '/Fixture/UsedPublicInternalPropertyInTestCaseOnly.php',
                __DIR__ . '/Source/TestCaseInternalPropertyUser.php',
            ],
            [],
        ];

        $errorMessage = sprintf(
            UnusedPublicPropertyRule::ERROR_MESSAGE,
            UnusedPublicInternalProperty::class,
            'property',
        );
        yield [
            [
                __DIR__ . '/Fixture/UnusedPublicInternalProperty.php',
            ],
            [
                [$errorMessage, 7, RuleTips::SOLUTION_MESSAGE],
            ],
        ];

        yield [
            [
                __DIR__ . '/Fixture/UsedInternalClassPublicPropertyInTestCaseOnly.php',
                __DIR__ . '/Source/TestCaseInternalPropertyUser.php',
            ],
            [],
        ];

        $errorMessage = sprintf(
            UnusedPublicPropertyRule::ERROR_MESSAGE,
            UnusedInternalClassPublicProperty::class,
            'property',
        );
        yield [
            [
                __DIR__ . '/Fixture/UnusedInternalClassPublicProperty.php',
            ],
            [
                [$errorMessage, 10, RuleTips::SOLUTION_MESSAGE],
            ],
        ];

        yield [
            [
                __DIR__ . '/Fixture/UsedPublicInternalPropertyInTestCaseOnly.php',
                __DIR__ . '/Fixture/UsedPublicInternalSubpropertyInTestCaseOnly.php',
                __DIR__ . '/Source/TestCaseInternalPropertyUser.php',
            ],
            [],
        ];

        yield [
            [
                __DIR__ . '/Fixture/UsedInternalClassPublicPropertyInTestCaseOnly.php',
                __DIR__ . '/Fixture/UsedInternalSubclassPublicPropertyInTestCaseOnly.php',
                __DIR__ . '/Source/TestCaseInternalPropertyUser.php',
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
