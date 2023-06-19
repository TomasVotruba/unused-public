<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Symfony;

final class SkipRequiredMethodCall
{
    /**
     * @Required()
     */
    public function autowireSomething(): void
    {
    }
}
