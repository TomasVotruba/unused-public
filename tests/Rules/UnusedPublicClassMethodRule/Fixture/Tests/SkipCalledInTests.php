<?php

declare(strict_types=1);

namespace Rules\UnusedPublicClassMethodRule\Fixture\Tests;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Tests\MethodForTests;

final class SkipCalledInTests extends TestCase
{
    public function testSomething(): void
    {
        MethodForTests::calledStaticCall();
    }
}
