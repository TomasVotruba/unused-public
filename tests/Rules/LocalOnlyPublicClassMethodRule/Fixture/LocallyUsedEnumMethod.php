<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture;

enum LocallyUsedEnumMethod
{
    case X;
    case Y;

    public function runHere()
    {
    }

    private function run()
    {
        $this->runHere();
    }
}
