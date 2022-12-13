<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

final class LocallyUsedPublicMethod
{
    public function runHere()
    {
    }

    private function run()
    {
        $this->runHere();
    }
}
