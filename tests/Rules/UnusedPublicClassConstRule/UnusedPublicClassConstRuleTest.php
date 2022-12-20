<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use TomasVotruba\UnusedPublic\Collectors\ClassConstFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassLikeConstCollector;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Rules\UnusedPublicClassConstRule;

/**
 * @extends RuleTestCase<UnusedPublicClassConstRule>
 */
final class UnusedPublicClassConstRuleTest extends RuleTestCase
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
        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'UNUSED');
        yield [[__DIR__ . '/Fixture/UnusedPublicConstant.php'], [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]]];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'UNUSED');
        yield [[__DIR__ . '/Fixture/UnusedPublicConstantFromInterface.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'LOCALLY_ONLY');
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstant.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'LOCALLY_ONLY_NAMED');
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstantByName.php'],
            [[$errorMessage, 9, RuleTips::SOLUTION_MESSAGE]], ];

        yield [[__DIR__ . '/Fixture/SkipApiPublicConstant.php'], []];
        yield [[__DIR__ . '/Fixture/SkipPrivateConstant.php'], []];
        yield [[__DIR__ . '/Fixture/SkipApiClassPublicConstant.php'], []];
        yield [[__DIR__ . '/Fixture/SkipUsedPublicConstant.php', __DIR__ . '/Source/ConstantUser.php'], []];
        yield [[
            __DIR__ . '/Fixture/SkipUsedConstantInSubclass.php', __DIR__ . '/Source/ConstantUserFromSubclass.php', ],
            [],
        ];

        yield [[__DIR__ . '/Fixture/SkipInterfaceConstantUsed.php', __DIR__ . '/Source/InterfaceConstantUser.php'], []];
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
