<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use Twig\Extension\AbstractExtension;

final class SkipPublicMethodInTwigExtension extends AbstractExtension
{
    public function someFilterMethod()
    {
    }

    public function getLoader()
    {
    }
}
