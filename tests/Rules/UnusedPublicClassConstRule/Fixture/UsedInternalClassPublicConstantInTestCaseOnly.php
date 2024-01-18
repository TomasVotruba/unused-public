<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

/**
 * @internal
 */
final class UsedInternalClassPublicConstantInTestCaseOnly
{
    public const USE_ME = 'not here';
}
