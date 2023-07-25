<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\UsedInTestCaseOnly;

final class TestCaseUser extends TestCase
{
    private function go(UsedInTestCaseOnly $usedInTestCaseOnly)
    {
        $usedInTestCaseOnly->useMe();

        UsedInTestCaseOnly::useMeStatic();
    }
}
