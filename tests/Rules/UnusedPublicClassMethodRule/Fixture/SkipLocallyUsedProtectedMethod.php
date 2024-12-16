<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class SkipLocallyUsedProtectedMethod
{
    protected function runHere()
    {
    }

    private function run()
    {
        $this->runHere();
    }
}
