<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\Fixture;

final class SkipProtectedApiMethodInFinalClass
{
    public function publicMethod()
    {
    }

    /**
     * @api
     */
    protected function protectedMethod()
    {
    }

    private function privateMethod()
    {
    }

    static public function staticPublicMethod()
    {
    }

    /**
     * @api
     */
    static protected function staticProtectedMethod()
    {
    }

    static private function staticPrivateMethod()
    {
    }
}
