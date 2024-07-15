<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\TypeWithClassName;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<PropertyFetch, string[]>
 */
final readonly class PublicPropertyFetchCollector implements Collector
{
    public function __construct(
        private Configuration $configuration,
        private ClassTypeDetector $classTypeDetector,
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return PropertyFetch::class;
    }

    /**
     * @param PropertyFetch $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return null;
        }

        // skip local
        if ($node->var instanceof Variable && $node->var->name === 'this') {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        $result = [];
        $propertyFetcherType = $scope->getType($node->var);
        foreach($propertyFetcherType->getObjectClassReflections() as $classReflection) {
            if ($this->classTypeDetector->isTestClass($classReflection)) {
                continue;
            }

            $propertyName = $node->name->toString();

            $result[] = $classReflection->getName() . '::' . $propertyName;
        }

        return $result;
    }
}
