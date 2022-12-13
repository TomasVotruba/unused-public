<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestPublicMethod extends TestCase
{
    public function testSomething()
    {
    }
}
