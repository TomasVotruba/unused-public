<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;

final class CaseInsensitiveClassName
{
    public function testWrongCasing(CALLER1 $caller1) // intentional wrong case
    {
        $caller1->callIt();
    }
}
