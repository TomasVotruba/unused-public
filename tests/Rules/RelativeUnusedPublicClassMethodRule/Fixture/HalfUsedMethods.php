<?php

namespace TomasVotruba\UnusedPublic\Tests\Rules\RelativeUnusedPublicClassMethodRule\Fixture;

final class HalfUsedMethods
{
    public function notUsed()
    {
    }

    public function alsoNotUsed()
    {
    }

    private function runMe()
    {
        $this->notUsed();
    }
}
