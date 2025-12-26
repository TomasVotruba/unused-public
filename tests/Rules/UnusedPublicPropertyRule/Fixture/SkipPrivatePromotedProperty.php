<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class SkipPrivatePromotedProperty
{
    public function __construct(
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
