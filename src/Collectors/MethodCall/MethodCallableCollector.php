<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\MethodCallableNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use TomasVotruba\UnusedPublic\ClassMethodCallReferenceResolver;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;

/**
 * @implements Collector<MethodCallableNode, array<string>|null>
 */
final class MethodCallableCollector implements Collector
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassMethodCallReferenceResolver $classMethodCallReferenceResolver,
        private readonly Configuration $configuration,
        private readonly ClassTypeDetector $classTypeDetector,
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCallableNode::class;
    }

    /**
     * @param MethodCallableNode $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        // unable to resolve method name
        if ($node->getName() instanceof Expr) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // skip calls in tests, as they are not used in production
        if ($this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        $classMethodReferences = [];
        $classMethodCallReferences = $this->classMethodCallReferenceResolver->resolve($node->getOriginalNode(), $scope);

        foreach ($classMethodCallReferences as $classMethodCallReference) {
            $className = $classMethodCallReference->getClass();
            $methodName = $classMethodCallReference->getMethod();

            $classMethodReference = $className . '::' . $methodName;
            if ($classMethodCallReference->isLocal()) {
                $classMethodReference = ReferenceMarker::LOCAL . $classMethodReference;
            }

            $classMethodReferences[] = $classMethodReference;

            foreach ($this->findParentClassMethodReferences($className, $methodName) as $parentClassMethodReference) {
                $classMethodReferences[] = $parentClassMethodReference;
            }
        }

        return $classMethodReferences;
    }

    /**
     * @return string[]
     */
    private function findParentClassMethodReferences(string $className, string $methodName): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $classMethodReferences = [];
        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasNativeMethod($methodName)) {
                $classMethodReferences[] = $parentClassReflection->getName() . '::' . $methodName;
            }
        }

        return $classMethodReferences;
    }
}
