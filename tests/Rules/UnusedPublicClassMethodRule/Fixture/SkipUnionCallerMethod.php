<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller2;

final class SkipUnionCallerMethod
{
    /**
     * @var Caller1|Caller2
     */
    private $caller;

    /**
     * @param Caller1|Caller2 $caller
     */
    public function __construct($caller)
    {
        $this->caller = $caller;
    }

    public function run(): void
    {
        $this->caller->callIt();
    }
}
