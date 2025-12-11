<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class SkipProtectedPromotedProperty
{
    public function __construct(
        protected string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
