<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class SkipPublicApiClassMethod
{
    /**
     * @api
     */
    public function freeForAll()
    {
    }
}
