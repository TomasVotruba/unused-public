<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class LocallyUsedPromotedProperty
{
    public function __construct(
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
