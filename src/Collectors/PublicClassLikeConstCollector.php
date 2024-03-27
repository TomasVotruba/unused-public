<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\StmtAnalyzers\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\InternalStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\RequiredStmtAnalyzer;

/**
 * @implements Collector<ClassConst, array<array{class-string, string, int}>>
 */
final class PublicClassLikeConstCollector implements Collector
{
    public function __construct(
        private readonly ApiDocStmtAnalyzer $apiDocStmtAnalyzer,
        private readonly InternalStmtAnalyzer $internalStmtAnalyzer,
        private readonly RequiredStmtAnalyzer $requiredStmtAnalyzer,
        private readonly Configuration $configuration,
    ) {
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

        if (! $node->isPublic()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (
            $this->apiDocStmtAnalyzer->isDoc($node, $classReflection) ||
            $this->internalStmtAnalyzer->isDoc($node, $classReflection) ||
            $this->requiredStmtAnalyzer->isDoc($node, $classReflection)
        ) {
            return null;
        }

        $constantNames = [];
        foreach ($node->consts as $constConst) {
            $constantNames[] = [$classReflection->getName(), $constConst->name->toString(), $node->getLine()];
        }

        return $constantNames;
    }
}
