<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture\SkipNullableUsedPublicMethod;

final class NullableClassMethodCaller
{
    private function go(?SkipNullableUsedPublicMethod $skipNullableUsedPublicMethod)
    {
        $skipNullableUsedPublicMethod->useMeMaybe();
    }
}
