<?php

declare(strict_types=1);

namespace Rules\UnusedPublicClassMethodRule\Fixture\Tests;

use PHPUnit\Framework\TestCase;

final class SkipCalledInTests extends TestCase
{
    public function testSomething(): void
    {
        MethodForTests::someStaticCall();
    }
}
