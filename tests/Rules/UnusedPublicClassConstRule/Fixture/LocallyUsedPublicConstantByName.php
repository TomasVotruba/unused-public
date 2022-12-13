<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Fixture;

final class LocallyUsedPublicConstantByName
{
    public const LOCALLY_ONLY_NAMED = 'public is not correct';

    public function run()
    {
        return self::LOCALLY_ONLY_NAMED;
    }
}
