<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source\Repository;

abstract class AbstractRepository
{
    public function fetchAll(): array
    {
        return [];
    }
}
