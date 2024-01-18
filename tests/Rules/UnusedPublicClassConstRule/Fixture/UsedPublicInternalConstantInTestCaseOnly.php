<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

final class UsedPublicInternalConstantInTestCaseOnly
{
    /**
     * @internal
     */
    public const USE_ME = 'not here';
}
