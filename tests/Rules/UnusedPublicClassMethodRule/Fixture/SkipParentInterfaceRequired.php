<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Twig\TwigExtensionFactory;

final class SkipParentInterfaceRequired
{
    public function __construct(TwigExtensionFactory $twigExtensionFactory)
    {
        $twigExtension = $twigExtensionFactory->create();
        $someLoader = $twigExtension->getLoader();
    }
}
