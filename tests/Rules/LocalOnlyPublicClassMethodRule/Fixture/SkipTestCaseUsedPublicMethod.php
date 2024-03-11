<?php

declare(strict_types=1);

namespace Rules\LocalOnlyPublicClassMethodRule\Fixture;

final class SkipTestCaseUsedPublicMethod
{
    public function runHere()
    {
    }

    private function run()
    {
        $this->runHere();
    }
}
