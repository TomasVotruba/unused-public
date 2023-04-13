<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\SomeTrait;

final class SkipTraitMethod
{
    use SomeTrait;

    public function testSomething()
    {
        $this->useMe(); // used method from trait
    }
}
