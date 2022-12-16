<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<Class_, array<array{class-string, string, int}>>
 */
final class PublicStaticPropertyCollector implements Collector
{
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer
     */
    private $apiDocStmtAnalyzer;
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;
    public function __construct(ApiDocStmtAnalyzer $apiDocStmtAnalyzer, Configuration $configuration)
    {
        $this->apiDocStmtAnalyzer = $apiDocStmtAnalyzer;
        $this->configuration = $configuration;
    }
    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return array<array{string, string, int}>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedStaticPropertyEnabled()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->apiDocStmtAnalyzer->isApiDoc($node, $classReflection)) {
            return null;
        }

        $classLike = $node->getOriginalNode();
        if (! $classLike instanceof Class_) {
            return null;
        }

        $staticPropertyNames = [];
        foreach ($classLike->getProperties() as $property) {
            if (! $property->isPublic()) {
                continue;
            }

            if (! $property->isStatic()) {
                continue;
            }

            foreach ($property->props as $propertyProperty) {
                $staticPropertyNames[] = [
                    $classReflection->getName(),
                    $propertyProperty->name->toString(),
                    $node->getLine(),
                ];
            }
        }

        return $staticPropertyNames;
    }
}
