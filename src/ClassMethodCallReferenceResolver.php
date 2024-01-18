<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeCombinator;
use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

final class ClassMethodCallReferenceResolver
{
    public function __construct(
        private readonly ClassTypeDetector $classTypeDetector,
    ) {
    }

    /**
     * @return MethodCallReference[]
     */
    public function resolve(MethodCall $methodCall, Scope $scope): array
    {
        if ($methodCall->name instanceof Expr) {
            return [];
        }

        $callerType = $scope->getType($methodCall->var);

        // remove optional nullable type
        if (TypeCombinator::containsNull($callerType)) {
            $callerType = TypeCombinator::removeNull($callerType);
        }

        // unwrap this type, as method is used
        $isLocal = false;

        if ($callerType instanceof ThisType) {
            $callerType = $callerType->getStaticObjectType();
            $isLocal = true;
        }

        $classReflection = $scope->getClassReflection();
        $isTest = $classReflection instanceof ClassReflection && $this->classTypeDetector->isTestClass($classReflection);

        $methodCallReferences = [];
        foreach ($callerType->getReferencedClasses() as $className) {
            $methodCallReferences[] = new MethodCallReference($className, $methodCall->name->toString(), $isLocal, $isTest);
        }

        return $methodCallReferences;
    }
}
