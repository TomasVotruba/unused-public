<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Repository;

abstract class AbstractRepository
{
    public function fetchAll(): array
    {
        return [];
    }
}
