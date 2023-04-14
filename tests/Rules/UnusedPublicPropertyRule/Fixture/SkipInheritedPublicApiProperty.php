<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\BasePublicPropertyClass;

final class SkipInheritedApiPublicPublicProperty extends BasePublicPropertyClass
{
    public $property = 'overridden!';
}


