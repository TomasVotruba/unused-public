<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture;

use Rules\LocalOnlyPublicClassMethodRule\Source\SomeParentWithInterface;

final class SkipInterfaceMethod implements SomeParentWithInterface
{
    /**
     * This method is require by parent interface,
     * and should never change visibility
     */
    public function neverReport(): void
    {
        $url = $this->publicFormatUrl('a', 'b');
    }

    public function publicFormatUrl($path, $type): string
    {
        return '';
    }
}
