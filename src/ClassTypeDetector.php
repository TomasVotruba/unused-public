<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PHPStan\Reflection\ClassReflection;

final class ClassTypeDetector
{
    public function isTestClass(ClassReflection $classReflection): bool
    {
        if ($classReflection->isSubclassOf('PHPUnit\Framework\TestCase')) {
            return true;
        }

        if ($classReflection->isSubclassOf('PHPUnit_Framework_TestCase')) {
            return true;
        }

        return $classReflection->implementsInterface('Behat\Behat\Context\Context');
    }
}
