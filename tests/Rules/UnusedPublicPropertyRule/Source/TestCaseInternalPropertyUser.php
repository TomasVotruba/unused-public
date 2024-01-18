<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedInternalClassPublicPropertyInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedInternalSubclassPublicPropertyInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedPublicInternalPropertyInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\UsedPublicInternalSubpropertyInTestCaseOnly;

final class TestCaseInternalPropertyUser extends TestCase
{
    private function go1()
    {
        $o = new UsedPublicInternalPropertyInTestCaseOnly();
        $o->property = 'a value';
    }

    private function go2()
    {
        $o = new UsedInternalClassPublicPropertyInTestCaseOnly();
        $o->property = 'a value';
    }

    private function go3()
    {
        $o = new UsedPublicInternalSubpropertyInTestCaseOnly();
        $o->property = 'a value';
    }

    private function go4()
    {
        $o = new UsedInternalSubclassPublicPropertyInTestCaseOnly();
        $o->property = 'a value';
    }
}
