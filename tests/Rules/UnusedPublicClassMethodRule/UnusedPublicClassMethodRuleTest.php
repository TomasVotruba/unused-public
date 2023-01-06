<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use TomasVotruba\UnusedPublic\Collectors\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicClassMethodRule;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Interface\InterfaceWithExtraMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\StaticPublicMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\UsedInTestCaseOnly;

final class UnusedPublicClassMethodRuleTest extends RuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param string[] $filePaths
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        yield [[__DIR__ . '/Fixture/SkipSymfonyValidatorMethod.php'], []];
        yield [[__DIR__ . '/Fixture/SkipLocallyUsedPublicMethod.php'], []];

        $errorMessage = sprintf(
            UnusedPublicClassMethodRule::ERROR_MESSAGE,
            InterfaceWithExtraMethod::class,
            'extraMethod'
        );
        yield [[__DIR__ . '/Fixture/Interface/InterfaceWithExtraMethod.php'],
            [[$errorMessage, 15, RuleTips::SOLUTION_MESSAGE]],
        ];

        $errorMessage = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, StaticPublicMethod::class, 'runHere');
        yield [[
            __DIR__ . '/Source/StaticCalls.php',
            __DIR__ . '/Fixture/StaticPublicMethod.php',
        ], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];

        // public methods expected
        yield [[__DIR__ . '/Fixture/SkipTestPublicMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Controller/SkipControllerMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Controller/SkipNoRoutingControllerMethod.php'], []];

        // method required by parent
        yield [[__DIR__ . '/Fixture/SkipParentMethodOverride.php'], []];
        yield [[__DIR__ . '/Fixture/Interface/SkipImplementsInterfaceCoveredByContract.php'], []];

        yield [[__DIR__ . '/Fixture/SkipAcceptanceCest.php'], []];
        yield [[__DIR__ . '/Fixture/SkipClassWithAttribute.php'], []];
        yield [[__DIR__ . '/Fixture/SkipPublicApiClassMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Interface/SkipInterfaceMethod.php'], []];
        yield [[__DIR__ . '/Fixture/SkipPrivateClassMethod.php'], []];
        yield [[__DIR__ . '/Fixture/SkipTwigEntityWithMethods.php'], []];

        // this method is required by parent contract, and should be skipped
        yield [[
            __DIR__ . '/Fixture/SkipParentInterfaceRequired.php',
            __DIR__ . '/Source/Twig/ExistingTwigExtension.php',
        ], []];

        yield [[__DIR__ . '/Fixture/SkipUsedPublicMethod.php', __DIR__ . '/Source/ClassMethodCaller.php'], []];

        yield [[
            __DIR__ . '/Fixture/SkipNullableUsedPublicMethod.php', __DIR__ . '/Source/NullableClassMethodCaller.php', ],
            [],
        ];

        yield [[__DIR__ . '/Fixture/SkipPublicMethodInTwigExtension.php'], []];
        $errorMessage = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, UsedInTestCaseOnly::class, 'useMe');
        yield [[
            __DIR__ . '/Fixture/UsedInTestCaseOnly.php',
            __DIR__ . '/Source/TestCaseUser.php',
        ], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];

        // parent abstract method used by child call
        yield [[
            __DIR__ . '/Fixture/SkipChildUsedPublicMethod.php',
            __DIR__ . '/Source/Repository/AbstractRepository.php',
            __DIR__ . '/Source/Repository/ChildRepository.php',
        ], []];
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
