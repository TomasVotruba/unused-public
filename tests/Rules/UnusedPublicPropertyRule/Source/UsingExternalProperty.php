<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicPropertyRule\Source;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicPropertyRule\Fixture\LocalyUsedPublicProperty;

final class UsingExternalProperty
{
    public function run(LocalyUsedPublicProperty $localyUsedPublicProperty)
    {
        return $localyUsedPublicProperty->name;
    }
}
