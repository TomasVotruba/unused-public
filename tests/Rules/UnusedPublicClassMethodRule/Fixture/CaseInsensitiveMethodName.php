<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1;

final class CaseInsensitiveMethodName
{
    public function testWrongCasing(Caller1 $caller1)
    {
        $caller1->CALLIT(); // intentional wrong case
    }
}
