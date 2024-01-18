<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\ConstantReference\ParentConstantReferenceResolver;

/**
 * @implements Collector<ClassConstFetch, string[]>
 */
final class ClassConstFetchCollector implements Collector
{
    public function __construct(
        private readonly ParentConstantReferenceResolver $parentConstantReferenceResolver,
        private readonly Configuration $configuration,
        private readonly ClassTypeDetector $classTypeDetector,
    ) {
    }

    public function getNodeType(): string
    {
        return ClassConstFetch::class;
    }

    /**
     * @param ClassConstFetch $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedConstantsEnabled()) {
            return [];
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        $className = $node->class->toString();
        $constantName = $node->name->toString();

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection) {
            if ($this->classTypeDetector->isTestClass($classReflection)) {
                return null;
            }

            if ($classReflection->hasConstant($constantName)) {
                $constantReflection = $classReflection->getConstant($constantName);
                $declaringClass = $constantReflection->getDeclaringClass();
                if ($declaringClass->getFileName() !== $classReflection->getFileName()) {
                    $declaringClassName = $declaringClass->getName();

                    $constantReferences = [$declaringClassName . '::' . $constantName];
                    $parentConstantReferences = $this->parentConstantReferenceResolver->findParentConstantReferences($declaringClassName, $constantName);
                    $constantReferences = [...$constantReferences, ...$parentConstantReferences];

                    return $constantReferences;
                }

                return null;
            }
        }

        $constantReferences = [$className . '::' . $constantName];
        $parentConstantReferences = $this->parentConstantReferenceResolver->findParentConstantReferences($className, $constantName);
        $constantReferences = [...$constantReferences, ...$parentConstantReferences];

        return $constantReferences;
    }
}
