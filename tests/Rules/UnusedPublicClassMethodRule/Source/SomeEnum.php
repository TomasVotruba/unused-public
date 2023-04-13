<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

enum SomeEnum
{
    public function unused()
    {
    }

    static public function unusedStatic()
    {
    }

    public function useMe()
    {
    }

    static public function useMeStatic()
    {
    }

    protected function unusedProtected()
    {
    }

    private function unusedPrivate()
    {
    }
}


