<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocalyUsedPublicProperty;

final class UsingExternalProperty
{
    public function run(LocalyUsedPublicProperty $localyUsedPublicProperty)
    {
        return $localyUsedPublicProperty->name;
    }
}
