<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\SkipUsedPublicConstantInSubclass;

final class ConstantUserFromSubclass
{
    public function run2()
    {
        return SkipUsedPublicConstantInSubclass::USED_FROM_SUBCLASS;
    }
}
