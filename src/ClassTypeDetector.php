<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\ResolvedMethodReflection;

final class ClassTypeDetector
{
    public function isTestClass(ClassReflection $classReflection): bool
    {
        return $classReflection->isSubclassOf('PHPUnit\Framework\TestCase') || $classReflection->isSubclassOf(
                'PHPUnit_Framework_TestCase'
        );
    }

}
