<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Fixture\SkipUsedPublicConstantInSubclass;

final class ConstantUserFromSubclass
{
    public function run2()
    {
        return SkipUsedPublicConstantInSubclass::USED_FROM_SUBCLASS;
    }
}
