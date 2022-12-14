<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class LocallyUsedStaticProperty
{
    public static $somePublicStaticProperty;

    private function run()
    {
        return self::$somePublicStaticProperty;
    }
}
