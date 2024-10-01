<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\ResolvedMethodReflection;

final class MethodTypeDetector
{
    public function isTestMethod(ClassMethod $classMethod, Scope $scope): bool
    {
        $classMethodName = $classMethod->name->toString();
        if (strncmp($classMethodName, 'test', strlen('test')) === 0) {
            return true;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $extendedMethodReflection = $classReflection->getMethod($classMethodName, $scope);

        if ($extendedMethodReflection->getDocComment() === null) {
            return false;
        }

        return strpos($extendedMethodReflection->getDocComment(), '@test') !== false;
    }

    public function isTraitMethod(ClassMethod $classMethod, Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $extendedMethodReflection = $classReflection->getMethod($classMethod->name->toString(), $scope);
        if ($extendedMethodReflection instanceof PhpMethodReflection || $extendedMethodReflection instanceof ResolvedMethodReflection) {
            return $extendedMethodReflection->getDeclaringTrait() instanceof ClassReflection;
        }

        return false;
    }
}
