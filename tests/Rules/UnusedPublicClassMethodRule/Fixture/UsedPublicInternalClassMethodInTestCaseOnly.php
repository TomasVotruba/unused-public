<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class UsedPublicInternalClassMethodInTestCaseOnly
{
    /**
     * @internal
     */
    public function useMe()
    {
    }

    /**
     * @internal
     */
    public static function useMeStatic()
    {
    }
}
