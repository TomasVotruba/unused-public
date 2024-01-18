<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\PropertyReference;

use PHPStan\Reflection\ReflectionProvider;

final class ParentPropertyReferenceResolver
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * @return string[]
     */
    public function findParentPropertyReferences(string $className, string $propertyName): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $propertyReferences = [];
        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasNativeProperty($propertyName)) {
                $propertyReferences[] = $parentClassReflection->getName() . '::' . $propertyName;
            }
        }

        return $propertyReferences;
    }
}
