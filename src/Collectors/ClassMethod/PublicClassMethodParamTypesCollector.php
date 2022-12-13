<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Collector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublicPHPStanRules\Matcher\Collector\PublicClassMethodMatcher;
use TomasVotruba\UnusedPublicPHPStanRules\PhpDoc\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublicPHPStanRules\Printer\CollectorMetadataPrinter;

/**
 * @implements Collector<ClassMethod, array{class-string, string, string, int}|null>
 */
final class PublicClassMethodParamTypesCollector implements Collector
{
    public function __construct(
        private ApiDocStmtAnalyzer $apiDocStmtAnalyzer,
        private PublicClassMethodMatcher $publicClassMethodMatcher,
        private CollectorMetadataPrinter $collectorMetadataPrinter
    ) {
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return array{class-string, string, string, int}|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if ($node->params === []) {
            return null;
        }

        if ($this->publicClassMethodMatcher->shouldSkipClassMethod($node)) {
            return null;
        }

        // only if the class has no parents/implementers, to avoid class method required by contracts
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->apiDocStmtAnalyzer->isApiDoc($node, $classReflection)) {
            return null;
        }

        if ($this->publicClassMethodMatcher->shouldSkipClassReflection($classReflection)) {
            return null;
        }

        $methodName = $node->name->toString();

        // is this method required by parent contract? skip it
        if ($this->publicClassMethodMatcher->isUsedByParentClassOrInterface($classReflection, $methodName)) {
            return null;
        }

        $printedParamTypesString = $this->collectorMetadataPrinter->printParamTypesToString($node);
        return [$classReflection->getName(), $methodName, $printedParamTypesString, $node->getLine()];
    }
}
