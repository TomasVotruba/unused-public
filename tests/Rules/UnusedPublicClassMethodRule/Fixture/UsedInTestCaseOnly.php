<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

final class UsedInTestCaseOnly
{
    public function useMe()
    {
    }

    static public function useMeStatic()
    {
    }
}
