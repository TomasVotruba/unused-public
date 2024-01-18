<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

/**
 * @internal
 */
class UsedInternalClassPublicPropertyInTestCaseOnly
{
    public $property = 'public';
}
