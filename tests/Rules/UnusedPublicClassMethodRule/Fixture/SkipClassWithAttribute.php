<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

use Symfony\Contracts\Service\Attribute\Required;

final class SkipClassWithAttribute
{
    #[Required]
    public function autowire()
    {
    }
}
