<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller2;

final class SkipUnionCallerMethod
{
    /**
     * @param Caller1|Caller2 $caller
     *
     * @api
     */
    public function run($caller): void
    {
        $caller->callIt();
    }
}
