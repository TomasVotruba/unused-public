<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\UsedInternalClassPublicClassMethodInTestCaseOnly;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\UsedPublicInternalClassMethodInTestCaseOnly;

final class TestCaseUser extends TestCase
{
    private function go1(UsedPublicInternalClassMethodInTestCaseOnly $usedPublicInternalClassMethodInTestCaseOnly)
    {
        $usedPublicInternalClassMethodInTestCaseOnly->useMe();

        UsedPublicInternalClassMethodInTestCaseOnly::useMeStatic();
    }

    private function go2(UsedInternalClassPublicClassMethodInTestCaseOnly $usedInternalClassPublicClassMethodInTestCaseOnly)
    {
        $usedInternalClassPublicClassMethodInTestCaseOnly->useMe();

        UsedInternalClassPublicClassMethodInTestCaseOnly::useMeStatic();
    }
}
