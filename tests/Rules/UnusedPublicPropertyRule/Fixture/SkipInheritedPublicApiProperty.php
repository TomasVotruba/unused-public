<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\BasePropertyClass;

final class SkipInheritedApiPublicProperty extends BasePropertyClass
{
    public $property = 'overridden!';
}


