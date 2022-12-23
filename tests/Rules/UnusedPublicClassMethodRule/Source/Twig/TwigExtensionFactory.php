<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Twig;

final class TwigExtensionFactory
{
    public function create(): ExistingTwigExtension
    {
        return new ExistingTwigExtension();
    }
}
