<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class SkipPublicCallbackMethod
{
    public function useMe()
    {
    }

    public static function useMeStatic()
    {
    }
}
