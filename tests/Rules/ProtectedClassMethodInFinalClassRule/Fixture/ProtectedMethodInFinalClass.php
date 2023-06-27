<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class ProtectedMethodInFinalClass
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
