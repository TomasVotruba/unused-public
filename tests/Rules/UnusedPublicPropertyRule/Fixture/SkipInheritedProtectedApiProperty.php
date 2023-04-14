<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\BaseProtectedPropertyClass;

final class SkipInheritedApiPublicPublicProperty extends BaseProtectedPropertyClass
{
    public $property = 'overridden!';
}


