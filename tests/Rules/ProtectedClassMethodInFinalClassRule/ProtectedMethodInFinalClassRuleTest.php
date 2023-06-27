<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\UnusedPublic\Rules\ProtectedMethodInFinalClassRule;

final class ProtectedMethodInFinalClassRuleTest extends RuleTestCase
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
        yield [[__DIR__ . '/Fixture/SkipOverriddenProtectedMethodInFinalClass.php'], []];
        yield [[__DIR__ . '/Fixture/SkipNonFinalClass.php'], []];
        yield [[__DIR__ . '/Fixture/SkipProtectedApiMethodInFinalClass.php'], []];

        yield [
            [__DIR__ . '/Fixture/ProtectedMethodInFinalClass.php'],
            [
                [sprintf(ProtectedMethodInFinalClassRule::ERROR_MESSAGE, 'protectedMethod'), 13],
                [sprintf(ProtectedMethodInFinalClassRule::ERROR_MESSAGE, 'staticProtectedMethod'), 25],
            ],
        ];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ProtectedMethodInFinalClassRule::class);
    }
}
