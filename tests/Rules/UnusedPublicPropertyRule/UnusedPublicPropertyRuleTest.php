<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicPropertyRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use TomasVotruba\UnusedPublicPHPStanRules\Collector\Class_\PublicPropertyCollector;
use TomasVotruba\UnusedPublicPHPStanRules\Collector\PropertyFetch\PublicPropertyFetchCollector;
use TomasVotruba\UnusedPublicPHPStanRules\Rules\UnusedPublicPropertyRule;

/**
 * @extends RuleTestCase<UnusedPublicPropertyRule>
 */
final class UnusedPublicPropertyRuleTest extends RuleTestCase
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
        $errorMessage = sprintf(UnusedPublicPropertyRule::ERROR_MESSAGE, 'name');
        yield [[__DIR__ . '/Fixture/LocalyUsedPublicProperty.php'],
            [[$errorMessage, 6, UnusedPublicPropertyRule::TIP_MESSAGE]], ];

        yield [
            [__DIR__ . '/Fixture/LocalyUsedPublicProperty.php', __DIR__ . '/Source/UsingExternalProperty.php'],
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
        ];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();
        return $container->getByType(UnusedPublicPropertyRule::class);
    }
}
