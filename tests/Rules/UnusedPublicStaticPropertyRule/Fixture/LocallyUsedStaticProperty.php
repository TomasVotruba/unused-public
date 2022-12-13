<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\DeadCode\UnusedPublicStaticPropertyRule\Fixture;

final class LocallyUsedStaticProperty
{
    public static $somePublicStaticProperty;

    private function run()
    {
        return self::$somePublicStaticProperty;
    }
}
