<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestCaseAnnotationMethod extends TestCase
{
    /**
     * @test
     */
    public function someMethodAnnotatedTest()
    {
    }
}
