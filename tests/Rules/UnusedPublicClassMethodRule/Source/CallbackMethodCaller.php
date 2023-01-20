<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\SkipPublicCallbackMethod;

final class CallbackMethodCaller
{
    private function go()
    {
        call_user_func([SkipPublicCallbackMethod::class, 'useMeStatic']);
    }

    private function goAgain(SkipPublicCallbackMethod $callbackMethod)
    {
        call_user_func([$callbackMethod, 'useMe']);
    }
}
