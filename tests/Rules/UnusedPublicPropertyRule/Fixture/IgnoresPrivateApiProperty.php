<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\BasePrivatePropertyClass;

final class IgnoresPrivateApiProperty extends BasePrivatePropertyClass
{
    public $property = 'overridden!';
}


