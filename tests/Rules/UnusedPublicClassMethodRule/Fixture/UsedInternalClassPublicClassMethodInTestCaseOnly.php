<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

/**
 * @internal
 */
final class UsedInternalClassPublicClassMethodInTestCaseOnly
{
    public function useMe()
    {
    }

    public static function useMeStatic()
    {
    }
}
