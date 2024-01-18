<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\CallReferece;

use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

final class CallReferencesFlatter
{
    /**
     * @param MethodCallReference[] $classMethodCallReferences
     * @return string[]
     */
    public function flatten(array $classMethodCallReferences): array
    {
        $classMethodReferences = [];

        foreach ($classMethodCallReferences as $classMethodCallReference) {
            $classMethodReferences[] = (string) $classMethodCallReference;
        }

        return $classMethodReferences;
    }
}
