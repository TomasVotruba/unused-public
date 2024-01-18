<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\TypeWithClassName;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\PropertyReference\ParentPropertyReferenceResolver;
use TomasVotruba\UnusedPublic\ValueObject\PropertyReference;

/**
 * @implements Collector<PropertyFetch, string[]>
 */
final class PublicPropertyFetchCollector implements Collector
{
    public function __construct(
        private readonly ParentPropertyReferenceResolver $parentPropertyReferenceResolver,
        private readonly Configuration $configuration,
        private readonly ClassTypeDetector $classTypeDetector,
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return PropertyFetch::class;
    }

    /**
     * @param PropertyFetch $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return null;
        }

        // skip local
        if ($node->var instanceof Variable && $node->var->name === 'this') {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        $propertyFetcherType = $scope->getType($node->var);
        if (! $propertyFetcherType instanceof TypeWithClassName) {
            return null;
        }

        $className = $propertyFetcherType->getClassName();
        $propertyName = $node->name->toString();

        $classReflection = $scope->getClassReflection();
        $isTest = $classReflection instanceof ClassReflection && $this->classTypeDetector->isTestClass($classReflection);

        $propertyReference = new PropertyReference($className, $propertyName, $isTest);
        $propertyReferences = [(string) $propertyReference];
        $parentPropertyReferences = $this->parentPropertyReferenceResolver->findParentPropertyReferences($className, $propertyName);

        return [...$propertyReferences, ...$parentPropertyReferences];
    }
}
