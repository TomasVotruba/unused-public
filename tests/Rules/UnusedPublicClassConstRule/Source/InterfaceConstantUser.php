<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture\SkipInterfaceConstantUsed;

final class InterfaceConstantUser
{
    public function value()
    {
        return SkipInterfaceConstantUsed::STATUS_ERROR;
    }
}
