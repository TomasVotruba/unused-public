<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;

final class SkipMixedUnionCallerMethod
{
    /**
     * @api
     */
    public function run(Caller1|int $caller): void
    {
        $caller->callIt();
    }
}
