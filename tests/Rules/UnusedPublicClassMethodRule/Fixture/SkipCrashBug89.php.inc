<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class SkipStringVariable
{
    /**
     * @api
     */
    public function run()
    {
        $classname = 'some';
        call_user_func([$classname, 'method']);
        $result = call_user_func(['some', 'method'], $args);
    }
}
