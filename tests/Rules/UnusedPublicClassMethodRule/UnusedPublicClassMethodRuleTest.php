<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\Callable_\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\Callable_\CallUserFuncCollector;
use TomasVotruba\UnusedPublic\Collectors\FormTypeClassCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicClassMethodRule;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Interface\InterfaceWithExtraMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\StaticPublicMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Tests\MethodForTests;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\UsedInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller2;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\SomeEnum;

final class UnusedPublicClassMethodRuleTest extends RuleTestCase
{
    #[DataProvider('provideData')]
    #[DataProvider('provideDataTests')]
    #[DataProvider('provideDataSymfony')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideDataTests(): Iterator
    {
        yield [[__DIR__ . '/Fixture/Tests/SkipTestCaseAnnotationMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Tests/SkipTestAnnotationMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Tests/SkipTestPublicMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Tests/SkipTestCasePublicMethod.php'], []];

        $errorMessage1 = sprintf(
            UnusedPublicClassMethodRule::ERROR_MESSAGE,
            MethodForTests::class,
            'notCalledStaticCall'
        );
        $errorMessage2 = sprintf(
            UnusedPublicClassMethodRule::ERROR_MESSAGE,
            MethodForTests::class,
            'calledStaticCall'
        );
        yield [[
            __DIR__ . '/Fixture/Tests/MethodForTests.php',
            __DIR__ . '/Fixture/Tests/SkipCalledInTests.php',
        ], [[$errorMessage2, 9, RuleTips::SOLUTION_MESSAGE], [$errorMessage1, 13, RuleTips::SOLUTION_MESSAGE]]];
    }

    public static function provideDataSymfony(): Iterator
    {
        yield [[__DIR__ . '/Fixture/Serializer/SkipSerializerMethod.php'], []];

        yield [[__DIR__ . '/Fixture/Symfony/SkipRequiredMethodCall.php'], []];
        yield [[__DIR__ . '/Fixture/Symfony/SkipSymfonyValidatorMethod.php'], []];

        yield [[
            __DIR__ . '/Fixture/Symfony/SomeFormType.php',
            __DIR__ . '/Fixture/Symfony/SkipEntityGetterSetters.php',
        ], []];
    }

    public static function provideData(): Iterator
    {
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
        yield [[__DIR__ . '/Fixture/Controller/SkipControllerMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Controller/SkipNoRoutingControllerMethod.php'], []];

        // method required by parent
        yield [[__DIR__ . '/Fixture/SkipParentMethodOverride.php'], []];
        yield [[__DIR__ . '/Fixture/Interface/SkipImplementsInterfaceCoveredByContract.php'], []];

        yield [[__DIR__ . '/Fixture/SkipClassWithAttribute.php'], []];
        yield [[__DIR__ . '/Fixture/SkipPublicApiClassMethod.php'], []];
        yield [[__DIR__ . '/Fixture/Interface/SkipInterfaceMethod.php'], []];

        yield [[__DIR__ . '/Fixture/SkipUsedPublicMethod.php', __DIR__ . '/Source/ClassMethodCaller.php'], []];

        yield [[__DIR__ . '/Source/CallbackMethodCaller.php', __DIR__ . '/Fixture/SkipPublicCallbackMethod.php'], []];

        // static call
        yield [[
            __DIR__ . '/Fixture/FirstClassCallable/DeserializeArrayToObjectCaller.php', __DIR__ . '/Fixture/FirstClassCallable/SkipDeserializeArrayToObject.php'],
            [],
        ];

        yield [[
            __DIR__ . '/Fixture/SkipNullableUsedPublicMethod.php', __DIR__ . '/Source/NullableClassMethodCaller.php', ],
            [],
        ];

        yield [[__DIR__ . '/Fixture/SkipPublicMethodInTwigExtension.php'], []];

        $errorMessage1 = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, UsedInTestCaseOnly::class, 'useMe');
        $errorMessage2 = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, UsedInTestCaseOnly::class, 'useMeStatic');
        yield [[
            __DIR__ . '/Fixture/UsedInTestCaseOnly.php',
            __DIR__ . '/Source/TestCaseUser.php',
        ], [[$errorMessage1, 9, RuleTips::SOLUTION_MESSAGE], [$errorMessage2, 13, RuleTips::SOLUTION_MESSAGE]]];

        // parent abstract method used by child call
        yield [[
            __DIR__ . '/Fixture/SkipChildUsedPublicMethod.php',
            __DIR__ . '/Source/Repository/AbstractRepository.php',
            __DIR__ . '/Source/Repository/ChildRepository.php',
        ], []];

        // laravel
        yield [[__DIR__ . '/Fixture/Laravel/SkipCommandHandle.php'], []];

        // Union types
        yield [[
            __DIR__ . '/Fixture/SkipUnionCallerMethod.php',
            __DIR__ . '/Source/Caller1.php',
            __DIR__ . '/Source/Caller2.php',
        ], []];
        yield [[
            __DIR__ . '/Fixture/SkipNativeUnionCallerMethod.php',
            __DIR__ . '/Source/Caller1.php',
            __DIR__ . '/Source/Caller2.php',
        ], []];
        $errorMessage = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, Caller2::class, 'callIt');
        yield [[
            __DIR__ . '/Fixture/SkipMixedUnionCallerMethod.php',
            __DIR__ . '/Source/Caller1.php',
            __DIR__ . '/Source/Caller2.php',
        ], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];

        // traits
        yield [[__DIR__ . '/Fixture/SkipTraitMethod.php', __DIR__ . '/Source/SomeTrait.php'], []];

        // enums
        $errorMessage1 = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, SomeEnum::class, 'unused');
        $errorMessage2 = sprintf(UnusedPublicClassMethodRule::ERROR_MESSAGE, SomeEnum::class, 'unusedStatic');
        yield [[
            __DIR__ . '/Fixture/EnumMethod.php',
            __DIR__ . '/Source/SomeEnum.php',
        ], [[$errorMessage1, 9, RuleTips::SOLUTION_MESSAGE], [$errorMessage2, 13, RuleTips::SOLUTION_MESSAGE]]];

        yield [[__DIR__ . '/Fixture/CaseInsensitiveMethodName.php', __DIR__ . '/Source/Caller1.php'], []];
        yield [[__DIR__ . '/Fixture/CaseInsensitiveClassName.php', __DIR__ . '/Source/Caller1.php'], []];

        // first class callables
        yield [[__DIR__ . '/Fixture/FirstClassCallable/SkipFirstClassCallableMethodCall.php'], []];

        yield [[__DIR__ . '/Fixture/plain.php', __DIR__ . '/Source/Caller1.php'], []];
        yield [[__DIR__ . '/Fixture/plain-call-user-func.php', __DIR__ . '/Source/Caller1.php'], []];
        yield [[__DIR__ . '/Fixture/SkipCrashBug89.php.inc'], []];

        yield [[__DIR__ . '/Fixture/SkipJsonSerialize.php'], []];
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
            self::getContainer()->getByType(CallUserFuncCollector::class),
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(UnusedPublicClassMethodRule::class);
    }
}
