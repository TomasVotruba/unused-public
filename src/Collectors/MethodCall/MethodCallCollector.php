<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\CallReferece\CallReferencesFlatter;
use TomasVotruba\UnusedPublic\CallReferece\ParentCallReferenceResolver;
use TomasVotruba\UnusedPublic\ClassMethodCallReferenceResolver;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<MethodCall, non-empty-array<string>|null>
 */
final readonly class MethodCallCollector implements Collector
{
    public function __construct(
        private ParentCallReferenceResolver $parentCallReferenceResolver,
        private ClassMethodCallReferenceResolver $classMethodCallReferenceResolver,
        private Configuration $configuration,
        private ClassTypeDetector $classTypeDetector,
        private CallReferencesFlatter $callReferencesFlatter,
    ) {
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

        // unable to resolve method name
        if ($node->name instanceof Expr) {
            return null;
        }

        // skip calls in tests, as they are not used in production
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        $classMethodCallReferences = $this->classMethodCallReferenceResolver->resolve($node, $scope);
        $classMethodReferences = $this->callReferencesFlatter->flatten($classMethodCallReferences);

        foreach ($classMethodCallReferences as $classMethodCallReference) {
            $parentClassMethodReferences = $this->parentCallReferenceResolver->findParentClassMethodReferences(
                $classMethodCallReference->getClass(),
                $classMethodCallReference->getMethod()
            );

            foreach ($parentClassMethodReferences as $parentClassMethodReference) {
                $classMethodReferences[] = $parentClassMethodReference;
            }
        }

        return $classMethodReferences;
    }
}
