<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Contract\MethodRequiredInterface;

final class SkipImplementsInterfaceCoveredByContract implements MethodRequiredInterface
{
    public function useMeMaybe()
    {
    }
}
