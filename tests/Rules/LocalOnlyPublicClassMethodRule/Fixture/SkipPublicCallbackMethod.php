<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture;

final class SkipPublicCallbackMethod
{
    public function runHere()
    {
    }

    private function run()
    {
        $this->runHere();
    }
}

function doFoo()
{
    $object = new SkipPublicCallbackMethod();
    call_user_func([$object, 'runHere']);
}
