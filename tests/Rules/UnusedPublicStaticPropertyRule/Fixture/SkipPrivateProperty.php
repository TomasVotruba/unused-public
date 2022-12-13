<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicStaticPropertyRule\Fixture;

final class SkipPrivateProperty
{
    private $somePublicStaticProperty;

    private function run()
    {
    }
}
