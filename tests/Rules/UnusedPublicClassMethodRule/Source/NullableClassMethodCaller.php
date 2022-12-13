<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\SkipNullableUsedPublicMethod;

final class NullableClassMethodCaller
{
    private function go(?SkipNullableUsedPublicMethod $skipNullableUsedPublicMethod)
    {
        $skipNullableUsedPublicMethod->useMeMaybe();
    }
}
