<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

class SkipNonFinalClass
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
