<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

use PHPUnit\Framework\TestCase;

final class TestCaseUser extends TestCase
{
    private function go()
    {
        $used = UsedInTestCaseOnly::USE_ME;
    }
}
