<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Source;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\LocallyUsedPublicInternalMethod;

final class TestCaseUser extends TestCase
{
    private function go(LocallyusedPublicInternalMethod $locallyusedPublicInternalMethod): void
    {
        $locallyusedPublicInternalMethod->runHere();
    }
}
