<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Fixture\SkipInterfaceConstantUsed;

final class InterfaceConstantUser
{
    public function value()
    {
        return SkipInterfaceConstantUsed::STATUS_ERROR;
    }
}
