<?php

declare(strict_types=1);

namespace Rules\LocalOnlyPublicClassMethodRule\Source;

use PHPUnit\Framework\TestCase;
use Rules\LocalOnlyPublicClassMethodRule\Fixture\SkipTestCaseUsedPublicMethod;

final class TestCaseUser extends TestCase
{
    private function go(SkipTestCaseUsedPublicMethod $skipTestCaseUsedPublicMethod)
    {
        $skipTestCaseUsedPublicMethod->runHere();
    }
}
