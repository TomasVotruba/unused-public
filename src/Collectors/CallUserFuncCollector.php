<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantArrayType;
use TomasVotruba\UnusedPublic\ClassMethodCallReferenceResolver;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;
use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

/**
 * @implements Collector<FuncCall, array<string>|null>
 */
final class CallUserFuncCollector implements Collector
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    /**
     * @param FuncCall $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        // skip calls in tests, as they are not used in production
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $classReflection->isSubclassOf(
            'PHPUnit\Framework\TestCase'
        )) {
            return null;
        }

        // unable to resolve method name
        if ($node->name instanceof Expr) {
            return null;
        }

        if (strtolower($node->name->toString()) !== 'call_user_func') {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 1) {
            return null;
        }

        $callableType = $scope->getType($args[0]->value);
        if (!$callableType instanceof ConstantArrayType) {
            return null;
        }

        $typeAndMethodNames = $callableType->findTypeAndMethodNames();
        if ($typeAndMethodNames === []) {
            return null;
        }

        $classMethodReferences = [];
        foreach($typeAndMethodNames as $typeAndMethodName) {
            $classMethodReferences[] = $typeAndMethodName->getType()->getClassName() . '::' . $typeAndMethodName->getMethod();
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
