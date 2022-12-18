<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\SkipExternallyUsedPublicStaticProperty;

final class AnotherClassUsingPublicStaticProperty
{
    public function run()
    {
        return SkipExternallyUsedPublicStaticProperty::$name;
    }
}
