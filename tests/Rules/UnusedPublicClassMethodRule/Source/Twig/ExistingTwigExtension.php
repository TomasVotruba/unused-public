<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Twig;

final class ExistingTwigExtension
{
    public function getLoader()
    {
        return 123;
    }
}
