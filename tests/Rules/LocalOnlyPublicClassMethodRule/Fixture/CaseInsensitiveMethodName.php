<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture;

final class CaseInsensitiveMethodName
{
    public function runHere()
    {
    }

    private function run()
    {
        $this->RUNHERE(); // intentional wrong case
    }
}
