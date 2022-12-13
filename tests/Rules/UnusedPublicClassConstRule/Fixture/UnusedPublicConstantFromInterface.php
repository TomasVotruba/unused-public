<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

interface UnusedPublicConstantFromInterface
{
    public const UNUSED = 'not here';
}
