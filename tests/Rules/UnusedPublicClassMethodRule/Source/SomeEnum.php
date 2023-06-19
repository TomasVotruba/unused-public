<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

enum SomeEnum
{
    public function unused()
    {
    }

    public static function unusedStatic()
    {
    }

    public function useMe()
    {
    }

    public static function useMeStatic()
    {
    }

    private function unusedProtected()
    {
    }

    private function unusedPrivate()
    {
    }
}
