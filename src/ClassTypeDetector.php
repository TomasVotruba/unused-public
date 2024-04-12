<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PHPStan\Reflection\ClassReflection;

final class ClassTypeDetector
{
    public function isTestClass(ClassReflection $classReflection): bool
    {
        return $classReflection->isSubclassOf('PHPUnit\Framework\TestCase')
            || $classReflection->isSubclassOf('PHPUnit_Framework_TestCase')
            || $classReflection->implementsInterface('Behat\Behat\Context\Context')
        ;
    }
}
