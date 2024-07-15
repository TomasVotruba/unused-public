<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

use PHPUnit\Framework\TestCase;
use Rules\UnusedPublicPropertyRule\Fixture\StaticUsedInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedInTestCaseOnly;

final class TestCaseUser extends TestCase
{
    private function go()
    {
        $o = new UsedInTestCaseOnly();
        $o->property = 'a value';

        $o = new StaticUsedInTestCaseOnly();
        $o::$property = 'a value';
    }
}
