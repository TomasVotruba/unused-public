<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

class LocallyUsedStaticPropertyViaStatic
{
    public static $somePublicStaticProperty;

    private function run()
    {
        return static::$somePublicStaticProperty;
    }
}
