<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use Livewire\Component;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\InternalDocStmtAnalyzer;

/**
 * @implements Collector<InClassNode, array<array{class-string, string, int}>>
 */
final class PublicPropertyCollector implements Collector
{
    /**
     * @var array<class-string<Component>>
     */
    private const CLASSES_TO_SKIP = ['Livewire\Component'];

    public function __construct(
        private readonly ApiDocStmtAnalyzer $apiDocStmtAnalyzer,
        private readonly InternalDocStmtAnalyzer $internalDocStmtAnalyzer,
        private readonly Configuration $configuration
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return array<array{string, string, int}>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $classLike = $node->getOriginalNode();
        if (! $classLike instanceof Class_) {
            return null;
        }

        if ($this->shouldSkipClass($classReflection, $classLike)) {
            return null;
        }

        $publicPropertyNames = [];
        foreach ($classLike->getProperties() as $property) {
            if (! $property->isPublic()) {
                continue;
            }

            foreach ($property->props as $propertyProperty) {
                $propertyName = $propertyProperty->name->toString();

                if ($this->shouldSkipProperty($classReflection, $propertyName, $scope)) {
                    continue;
                }

                $isInternal = $this->isInternalRecursive($classReflection, $classLike, $propertyName, $scope);

                $publicPropertyNames[] = [$classReflection->getName(), $propertyName, $node->getLine(), $isInternal];
            }
        }

        return $publicPropertyNames;
    }

    private function isInternalRecursive(ClassReflection $classReflection, Class_ $class, string $propertyName, Scope $scope): bool
    {
        if ($this->internalDocStmtAnalyzer->isInternalDoc($class, $classReflection)) {
            return true;
        }

        while (true) {
            if (! $classReflection->hasProperty($propertyName)) {
                return false;
            }

            $propertyReflection = $classReflection->getProperty($propertyName, $scope);

            $docComment = $propertyReflection->getDocComment();
            if ($docComment !== null && $this->internalDocStmtAnalyzer->isInternalDocComment($docComment)) {
                return true;
            }

            $classReflection = $classReflection->getParentClass();
            if (! $classReflection instanceof ClassReflection) {
                return false;
            }
        }
    }

    private function shouldSkipProperty(ClassReflection $classReflection, string $propertyName, Scope $scope): bool
    {
        if (! $classReflection->hasProperty($propertyName)) {
            return false;
        }

        $propertyReflection = $classReflection->getProperty($propertyName, $scope);
        // don't inherit doc from a private property
        if ($propertyReflection->isPrivate()) {
            return false;
        }

        $docComment = $propertyReflection->getDocComment();
        if ($docComment !== null && $this->apiDocStmtAnalyzer->isApiDocComment($docComment)) {
            return true;
        }

        if ($docComment !== null && $this->internalDocStmtAnalyzer->isInternalDocComment($docComment)) {
            return false;
        }

        $parentClassReflection = $classReflection->getParentClass();
        if (! $parentClassReflection instanceof ClassReflection) {
            return false;
        }

        return $this->shouldSkipProperty($parentClassReflection, $propertyName, $scope);
    }

    private function shouldSkipClass(ClassReflection $classReflection, Class_ $class): bool
    {
        foreach (self::CLASSES_TO_SKIP as $classToSkip) {
            if ($classReflection->isSubclassOf($classToSkip)) {
                return true;
            }
        }

        return $this->apiDocStmtAnalyzer->isApiDoc($class, $classReflection);
    }
}
