<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture\SkipUsedPublicMethod;

final class ClassMethodCaller
{
    private function go(SkipUsedPublicMethod $usedPublicMethod)
    {
        $usedPublicMethod->useMe();
    }
}
