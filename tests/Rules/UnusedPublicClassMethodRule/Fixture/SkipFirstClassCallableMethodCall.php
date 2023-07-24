<?php

declare(strict_types=1);

namespace Rules\UnusedPublicClassMethodRule\Fixture;

final class SkipFirstClassCallableMethodCall
{
    /**
     * @api
     */
    public function run()
    {
        return $this->anotherCall(...);
    }

    public function anotherCall()
    {
    }
}
