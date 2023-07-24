<?php

declare(strict_types=1);

namespace Rules\LocalOnlyPublicClassMethodRule\Source;

interface SomeParentWithInterface
{
    public function neverReport(): void;
}
