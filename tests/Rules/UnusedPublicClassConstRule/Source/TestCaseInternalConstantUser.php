<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

use PHPUnit\Framework\TestCase;

final class TestCaseInternalConstantUser extends TestCase
{
    private function go()
    {
        $used1 = UsedPublicInternalConstantInTestCaseOnly::USE_ME;
        $used2 = UsedInternalClassPublicConstantInTestCaseOnly::USE_ME;
    }
}
