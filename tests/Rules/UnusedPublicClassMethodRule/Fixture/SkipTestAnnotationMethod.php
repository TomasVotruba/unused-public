<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestAnnotationMethod // not extending TestCase
{
    public function testSomeMethodAnnotatedTest()
    {
    }
}
