<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<ClassConst, array<array{class-string, string, int}>>
 */
final class PublicClassLikeConstCollector implements Collector
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
    public function getNodeType(): string
    {
        return ClassConst::class;
    }

    /**
     * @param ClassConst $node
     * @return array<array{class-string, string, int}>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedConstantsEnabled()) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (! $node->isPublic()) {
            return null;
        }

        if ($this->apiDocStmtAnalyzer->isApiDoc($node, $classReflection)) {
            return null;
        }

        $constantNames = [];
        foreach ($node->consts as $constConst) {
            $constantNames[] = [$classReflection->getName(), $constConst->name->toString(), $node->getLine()];
        }

        return $constantNames;
    }
}
