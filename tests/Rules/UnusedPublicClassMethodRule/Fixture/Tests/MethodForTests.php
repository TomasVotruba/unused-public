<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Tests;

final class MethodForTests
{
    public static function calledStaticCall(): void
    {
    }

    public static function notCalledStaticCall(): void
    {
    }
}
