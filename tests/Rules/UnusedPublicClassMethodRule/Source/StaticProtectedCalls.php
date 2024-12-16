<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\StaticProtectedMethod;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\StaticPublicMethod;

final class StaticProtectedCalls
{
    private function go()
    {
        return StaticProtectedMethod::anotherMethod();
    }
}
