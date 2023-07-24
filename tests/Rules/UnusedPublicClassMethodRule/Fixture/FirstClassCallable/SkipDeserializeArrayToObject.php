<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\FirstClassCallable;

final class SkipDeserializeArrayToObject
{
    private function __construct(
        private readonly array $data,
    ) {
    }

    public static function deserialize(array $data): self
    {
        return new self($data);
    }
}
