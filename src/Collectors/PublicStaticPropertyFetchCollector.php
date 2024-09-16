<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<StaticPropertyFetch, non-empty-array<string>|null>
 */
final readonly class PublicStaticPropertyFetchCollector implements Collector
{
    public function __construct(
        private Configuration $configuration,
        private ClassTypeDetector $classTypeDetector,
    ) {
    }

    public function getNodeType(): string
    {
        return StaticPropertyFetch::class;
    }

    /**
     * @param StaticPropertyFetch $node
     * @return non-empty-array<string>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        if (
            $node->class instanceof Name
            && ($node->class->toString() === 'self' || $node->class->toString() === 'static')
        ) {
            // self fetch is allowed
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        $classType = $node->class instanceof Name ? $scope->resolveTypeByName($node->class) : $scope->getType(
            $node->class
        );

        $result = [];
        foreach ($classType->getObjectClassReflections() as $classReflection) {
            $propertyName = $node->name->toString();

            if (! $classReflection->hasProperty($propertyName)) {
                continue;
            }

            $propertyReflection = $classReflection->getProperty($propertyName, $scope);
            $result[] = $propertyReflection->getDeclaringClass()->getName() . '::' . $propertyName;
        }

        if ($result === []) {
            return null;
        }

        return $result;
    }
}
