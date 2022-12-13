<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicStaticPropertyRule\Fixture;

final class SkipPrivateProperty
{
    private $somePublicStaticProperty;

    private function run()
    {
    }
}
