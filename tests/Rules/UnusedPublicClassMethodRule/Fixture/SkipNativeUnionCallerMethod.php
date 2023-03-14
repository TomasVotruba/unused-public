<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller2;

final class SkipNativeUnionCallerMethod
{
    /**
     * @api
     */
    public function run(Caller1|Caller2 $caller): void
    {
        $caller->callIt();
    }
}
