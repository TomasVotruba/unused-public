<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Simpod;

final class SomeCaller
{
    public function test(): void
    {
        SomeClass::callMe();
    }
}
