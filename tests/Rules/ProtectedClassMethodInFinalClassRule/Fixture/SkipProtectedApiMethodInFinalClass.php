<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class SkipProtectedApiMethodInFinalClass
{
    public function publicMethod()
    {
    }

    public static function staticPublicMethod()
    {
    }

    /**
     * @api
     */
    private function protectedMethod()
    {
    }

    private function privateMethod()
    {
    }

    /**
     * @api
     */
    private static function staticProtectedMethod()
    {
    }

    private static function staticPrivateMethod()
    {
    }
}
