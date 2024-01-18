<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

final class UnusedPublicInternalConstant
{
    /**
     * @internal
     */
    public const UNUSED = 'not here';
}
