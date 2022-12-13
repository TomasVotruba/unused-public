<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symplify\PHPStanRules\Collector\ClassConst\ClassConstFetchCollector;
use Symplify\PHPStanRules\Collector\ClassConst\PublicClassLikeConstCollector;
use Symplify\PHPStanRules\Rules\DeadCode\UnusedPublicClassConstRule;

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
        yield [[__DIR__ . '/Fixture/UnusedPublicConstant.php'],
            [[$errorMessage, 9, UnusedPublicClassConstRule::TIP_MESSAGE]], ];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'UNUSED');
        yield [[__DIR__ . '/Fixture/UnusedPublicConstantFromInterface.php'],
            [[$errorMessage, 9, UnusedPublicClassConstRule::TIP_MESSAGE]], ];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'LOCALLY_ONLY');
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstant.php'],
            [[$errorMessage, 9, UnusedPublicClassConstRule::TIP_MESSAGE]], ];

        $errorMessage = sprintf(UnusedPublicClassConstRule::ERROR_MESSAGE, 'LOCALLY_ONLY_NAMED');
        yield [[__DIR__ . '/Fixture/LocallyUsedPublicConstantByName.php'],
            [[$errorMessage, 9, UnusedPublicClassConstRule::TIP_MESSAGE]], ];

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
        $publicClassLikeConstCollector = self::getContainer()->getByType(PublicClassLikeConstCollector::class);
        return [new ClassConstFetchCollector(), $publicClassLikeConstCollector];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();
        return $container->getByType(UnusedPublicClassConstRule::class);
    }
}
