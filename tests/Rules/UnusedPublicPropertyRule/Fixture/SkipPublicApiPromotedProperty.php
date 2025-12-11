<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class SkipPublicApiPromotedProperty
{
    /**
     * @param string $name
     */
    public function __construct(
        /** @api */
        public string $name,
    ) {
    }
}
