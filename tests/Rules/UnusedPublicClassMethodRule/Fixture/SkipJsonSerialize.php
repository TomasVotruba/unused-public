<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

class SkipJsonSerialize implements \JsonSerializable {
    public function jsonSerialize() : array {
        return [];
    }
}
