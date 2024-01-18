<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

/**
 * @internal
 */
final class UnusedInternalClassPublicConstant
{
    public const UNUSED = 'not here';
}
