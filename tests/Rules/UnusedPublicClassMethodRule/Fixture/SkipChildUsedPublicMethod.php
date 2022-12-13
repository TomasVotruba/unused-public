<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source\Repository\ChildRepository;

final class SkipChildUsedPublicMethod
{
    private function useMe(ChildRepository $childRepository)
    {
        return $childRepository->fetchAll();
    }
}
