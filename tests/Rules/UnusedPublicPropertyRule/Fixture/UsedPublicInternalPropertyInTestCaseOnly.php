<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

class UsedPublicInternalPropertyInTestCaseOnly
{
    /**
     * @internal
     */
    public $property = 'public';
}
