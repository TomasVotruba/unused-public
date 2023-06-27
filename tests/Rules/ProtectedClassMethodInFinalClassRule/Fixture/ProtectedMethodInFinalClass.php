<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class ProtectedMethodInFinalClass
{
    public function publicMethod()
    {
    }

    public static function staticPublicMethod()
    {
    }

    private function protectedMethod()
    {
    }

    private function privateMethod()
    {
    }

    private static function staticProtectedMethod()
    {
    }

    private static function staticPrivateMethod()
    {
    }
}
