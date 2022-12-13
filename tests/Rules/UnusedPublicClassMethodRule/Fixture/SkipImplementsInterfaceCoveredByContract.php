<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source\Contract\MethodRequiredInterface;

final class SkipImplementsInterfaceCoveredByContract implements MethodRequiredInterface
{
    public function useMeMaybe()
    {
    }
}
