<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeWithClassName;
use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

final class ClassMethodCallReferenceResolver
{
    public function resolve(MethodCall $methodCall, Scope $scope): ?MethodCallReference
    {
        if ($methodCall->name instanceof Expr) {
            return null;
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

        if (! $callerType instanceof TypeWithClassName) {
            return null;
        }

        // move to the class where method is defined, e.g. parent class defines the method, so it should be checked there
        $className = $callerType->getClassName();
        $methodName = $methodCall->name->toString();

        return new MethodCallReference($className, $methodName, $isLocal);
    }
}
