<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use Livewire\Component;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\StmtAnalyzers\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\InternalStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\RequiredStmtAnalyzer;

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
        private readonly InternalStmtAnalyzer $internalStmtAnalyzer,
        private readonly RequiredStmtAnalyzer $requiredStmtAnalyzer,
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

                $publicPropertyNames[] = [$classReflection->getName(), $propertyName, $node->getLine()];
            }
        }

        return $publicPropertyNames;
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
        if (
            $docComment !== null &&
            (
                $this->apiDocStmtAnalyzer->isDocComment($docComment) ||
                $this->internalStmtAnalyzer->isDocComment($docComment) ||
                $this->requiredStmtAnalyzer->isDocComment($docComment)
            )
        ) {
            return true;
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

        return
            $this->apiDocStmtAnalyzer->isDoc($class, $classReflection) ||
            $this->internalStmtAnalyzer->isDoc($class, $classReflection) ||
            $this->requiredStmtAnalyzer->isDoc($class, $classReflection);
    }
}
