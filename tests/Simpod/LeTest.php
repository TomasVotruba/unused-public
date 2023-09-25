<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Simpod;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Tests\Simpod\Factory\SomeFactory;

final class LeTest extends TestCase
{
    public function testHi(): void
    {
        $factory = new SomeFactory();

        self::assertNotNull($factory->create());
    }
}
