<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Fixture\SkipInterfaceConstantUsed;

final class InterfaceConstantUser
{
    public function value()
    {
        return SkipInterfaceConstantUsed::STATUS_ERROR;
    }
}
