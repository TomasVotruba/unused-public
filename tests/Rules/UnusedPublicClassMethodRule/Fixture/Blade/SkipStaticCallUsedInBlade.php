<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Blade;

final class SkipStaticCallUsedInBlade
{
    public static function countMeOut()
    {
    }
}
