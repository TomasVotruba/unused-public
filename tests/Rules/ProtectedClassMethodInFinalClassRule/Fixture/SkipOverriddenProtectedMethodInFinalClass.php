<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class SkipOverriddenProtectedMethodInFinalClass extends MiddleClass
{
    public function publicMethod()
    {
    }

    public static function staticPublicMethod()
    {
    }

    protected function protectedMethod()
    {
    }

    protected static function staticProtectedMethod()
    {
    }

    private function privateMethod()
    {
    }

    private static function staticPrivateMethod()
    {
    }
}

class MiddleClass extends BaseClass
{
    protected static function staticProtectedMethod()
    {
    }
}


class BaseClass
{
    protected function protectedMethod()
    {
    }
}
