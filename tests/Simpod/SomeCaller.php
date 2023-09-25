<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Simpod;

use PHPUnit\Framework\TestCase;

final class SomeCaller extends TestCase
{
    public function test(): void
    {
        SomeClass::callMe();
    }
}
