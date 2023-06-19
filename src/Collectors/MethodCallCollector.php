<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ReflectionProvider;
use TomasVotruba\UnusedPublic\ClassMethodCallReferenceResolver;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;

/**
 * @implements Collector<MethodCall, array<string>|null>
 */
final class MethodCallCollector implements Collector
{
    /**
     * @readonly
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    private $reflectionProvider;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\ClassMethodCallReferenceResolver
     */
    private $classMethodCallReferenceResolver;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        ClassMethodCallReferenceResolver $classMethodCallReferenceResolver,
        Configuration $configuration
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->classMethodCallReferenceResolver = $classMethodCallReferenceResolver;
        $this->configuration = $configuration;
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        // skip calls in tests, as they are not used in production
        if ($this->isPHPUnitTestCase($scope)) {
            return null;
        }

        // unable to resolve method name
        if ($node->name instanceof Expr) {
            return null;
        }

        $classMethodReferences = [];

        $classMethodCallReferences = $this->classMethodCallReferenceResolver->resolve($node, $scope);

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

    private function isPHPUnitTestCase(Scope $scope): bool
    {
        if (! $scope->isInClass()) {
            return false;
        }

        $classReflection = $scope->getClassReflection();
        return $classReflection->isSubclassOf('PHPUnit\Framework\TestCase') || $classReflection->isSubclassOf(
            'PHPUnit_Framework_TestCase'
        );
    }
}
