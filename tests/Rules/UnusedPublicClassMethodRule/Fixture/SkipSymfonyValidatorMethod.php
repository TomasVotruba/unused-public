<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use Symfony\Component\Validator\Constraints\Callback;

final class SkipSymfonyValidatorMethod
{
    #[Callback(callback: [self::class, 'skipMe'])]
    public string $methodName;

    public static function skipMe()
    {
    }
}
