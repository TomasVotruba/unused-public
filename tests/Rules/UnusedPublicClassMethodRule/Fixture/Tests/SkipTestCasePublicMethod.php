<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Tests;

use PHPUnit\Framework\TestCase;

final class SkipTestCasePublicMethod extends TestCase
{
    public function testSomething()
    {
    }
}
