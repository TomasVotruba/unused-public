<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use Symfony\Contracts\Service\Attribute\Required;

final class SkipClassWithAttribute
{
    #[Required]
    public function autowire()
    {
    }
}
