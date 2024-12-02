<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Collectors\ClassConstFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassLikeConstCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicClassConstRule;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\LocallyUsedPublicConstant;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\LocallyUsedPublicConstantByName;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\UnusedPublicConstant;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\UnusedPublicConstantFromInterface;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\UsedInTestCaseOnly;

final class UnusedPublicClassConstRuleTest extends RuleTestCase
{
    /**
     * @param string[] $filePaths
     * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    #[DataProvider('provideDataFromBladeTemplates')]
    public function testRule(array $filePaths, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorMessagesWithLines);
    }

    public static function provideDataFromBladeTemplates(): Iterator
    {
        yield [[__DIR__ . '/Fixture/Blade/RouteName.php'], []];
    }

    public static function provideData(): Iterator
    {
        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, UnusedPublicConstant::class, 'UNUSED');
        yield [[__DIR__ . '/Fixture/UnusedPublicConstant.php'], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];

        $errorMessage = sprintf(
            UnusedPublicClassConstRule::ERROR_MESSAGE,
            UnusedPublicConstantFromInterface::class,
            'UNUSED'
        );
        yield [[__DIR__ . '/Fixture/UnusedPublicConstantFromInterface.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        $errorMessage = sprintf(
            UnusedPublicClassConstRule::ERROR_MESSAGE,
            LocallyUsedPublicConstant::class,
            'LOCALLY_ONLY'
        );
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstant.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        $errorMessage = sprintf(
            UnusedPublicClassConstRule::ERROR_MESSAGE,
            LocallyUsedPublicConstantByName::class,
            'LOCALLY_ONLY_NAMED'
        );
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstantByName.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        yield [[__DIR__ . '/Fixture/SkipApiPublicConstant.php'], []];
        yield [[__DIR__ . '/Fixture/SkipApiClassPublicConstant.php'], []];
        yield [[__DIR__ . '/Fixture/SkipUsedPublicConstant.php', __DIR__ . '/Source/ConstantUser.php'], []];
        yield [[
            __DIR__ . '/Fixture/SkipUsedConstantInSubclass.php', __DIR__ . '/Source/ConstantUserFromSubclass.php', ],
            [],
        ];

        yield [[__DIR__ . '/Fixture/SkipInterfaceConstantUsed.php', __DIR__ . '/Source/InterfaceConstantUser.php'], []];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, UsedInTestCaseOnly::class, 'USE_ME');
        yield [[
            __DIR__ . '/Fixture/UsedInTestCaseOnly.php',
            __DIR__ . '/Source/TestCaseUser.php',
        ], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];
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
            self::getContainer()->getByType(PublicClassLikeConstCollector::class),
            self::getContainer()->getByType(ClassConstFetchCollector::class),
        ];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();
        return $container->getByType(UnusedPublicClassConstRule::class);
    }
}
