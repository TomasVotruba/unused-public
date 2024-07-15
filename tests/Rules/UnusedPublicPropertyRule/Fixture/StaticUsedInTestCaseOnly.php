<?php

declare(strict_types=1);

namespace Rules\UnusedPublicPropertyRule\Fixture;

final class StaticUsedInTestCaseOnly
{
    static public $property = 'public static';
}
