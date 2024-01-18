<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ConstantReference;

use PHPStan\Reflection\ReflectionProvider;

final class ParentConstantReferenceResolver
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * @return string[]
     */
    public function findParentConstantReferences(string $className, string $constantName): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $constantReferences = [];
        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasConstant($constantName)) {
                $constantReferences[] = $parentClassReflection->getName() . '::' . $constantName;
            }
        }

        return $constantReferences;
    }
}
