<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class SkipOverriddenProtectedMethodInFinalClass extends MiddleClass
{
    public function publicMethod()
    {
    }

    protected function protectedMethod()
    {
    }

    private function privateMethod()
    {
    }

    static public function staticPublicMethod()
    {
    }

    static protected function staticProtectedMethod()
    {
    }

    static private function staticPrivateMethod()
    {
    }
}

class MiddleClass extends BaseClass {

    static protected function staticProtectedMethod()
    {
    }
}


class BaseClass {
    protected function protectedMethod()
    {
    }
}
