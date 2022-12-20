<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use PHPUnit_Framework_TestCase;

final class SkipTestUnderscorePublicMethod extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {
    }
}
