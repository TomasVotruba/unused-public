<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture\SkipNullableUsedPublicMethod;

final class NullableClassMethodCaller
{
    private function go(?SkipNullableUsedPublicMethod $skipNullableUsedPublicMethod)
    {
        $skipNullableUsedPublicMethod->useMeMaybe();
    }
}
