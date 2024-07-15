<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class StaticUsedInTestCaseOnly
{
    static public $property = 'public static';
}
