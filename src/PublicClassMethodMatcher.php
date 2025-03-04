<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;

final class PublicClassMethodMatcher
{
    /**
     * @var string[]
     */
    private const SKIPPED_TYPES = [
        'Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator',
    ];

    /**
     * @readonly
     */
    private ClassTypeDetector $classTypeDetector;

    public function __construct(ClassTypeDetector $classTypeDetector)
    {
        $this->classTypeDetector = $classTypeDetector;
    }

    public function shouldSkipClassReflection(ClassReflection $classReflection): bool
    {
        // skip interface as required, traits as unable to detect for sure
        if ($classReflection->isInterface() || $classReflection->isTrait()) {
            return true;
        }

        if ($this->classTypeDetector->isTestClass($classReflection)) {
            return true;
        }

        foreach (self::SKIPPED_TYPES as $skippedType) {
            if ($classReflection->isSubclassOf($skippedType)) {
                return true;
            }
        }

        return false;
    }

    public function isUsedByParentClassOrInterface(ClassReflection $classReflection, string $methodName): bool
    {
        // is this method required by parent contract? skip it
        foreach ($classReflection->getInterfaces() as $parentInterfaceReflection) {
            if ($parentInterfaceReflection->hasMethod($methodName)) {
                return true;
            }
        }

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasMethod($methodName)) {
                return true;
            }
        }

        return false;
    }

    public function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPublic()) {
            return true;
        }

        if ($classMethod->isMagic()) {
            return true;
        }

        // skip attributes, there is some purpose for these
        if ($classMethod->attrGroups !== []) {
            return true;
        }

        $doc = $classMethod->getDocComment();

        // skip symfony controller action
        if (! $doc instanceof Doc) {
            return false;
        }

        if (strpos($doc->getText(), '@Route') !== false) {
            return true;
        }

        return strpos($doc->getText(), '@Required') !== false;
    }
}
