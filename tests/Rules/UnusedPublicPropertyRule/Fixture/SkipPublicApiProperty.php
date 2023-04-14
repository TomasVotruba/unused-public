<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

final class SkipPublicApiProperty
{
    /**
     * @api
     */
    public $property = 'default';
}
