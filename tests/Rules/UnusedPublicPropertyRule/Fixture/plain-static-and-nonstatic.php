<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\PublicPropertyClass;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\PublicStaticPropertyClass;

$o = new PublicPropertyClass();
$o->property = 'a value';

PublicStaticPropertyClass::$staticProperty = 'a value';
