<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

final class LocallyUsedPublicConstant
{
    public const LOCALLY_ONLY = 'public is not correct';

    public function run()
    {
        return self::LOCALLY_ONLY;
    }
}
