<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\SkipUsedPublicConstant;

final class ConstantUser
{
    public function run()
    {
        return SkipUsedPublicConstant::USED;
    }
}
