<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class StaticProtectedMethod
{
    protected static function runHere()
    {
    }

    protected static function anotherMethod()
    {
    }
}
