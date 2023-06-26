<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestPublicMethod // not extending TestCase
{
    public function testSomething()
    {
    }
}