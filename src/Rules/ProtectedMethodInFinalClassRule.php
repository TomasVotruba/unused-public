<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use Nette\Utils\Arrays;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassMethod;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\CollectorMapper\MethodCallCollectorMapper;
use TomasVotruba\UnusedPublic\Collectors\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\CallUserFuncCollector;
use TomasVotruba\UnusedPublic\Collectors\FormTypeClassCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider;
use TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\ProtectedClassMethodInFinalClassRule\ProtectedClassMethodInFinalClassRuleTest
 */
final class ProtectedMethodInFinalClassRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Protected method "%s" should be reduced to private visiblity';

    public function __construct(
        private readonly ApiDocStmtAnalyzer       $apiDocStmtAnalyzer,
    ) {}

    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param InClassMethodNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methodReflection = $node->getMethodReflection();
        $method = $node->getOriginalNode();

        if (!$method->isProtected()) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }

        if (!$classReflection->isFinal()) {
            return[];
        }

        $docComment = $methodReflection->getDocComment();
        if ($docComment !== null && $this->apiDocStmtAnalyzer->isApiDocComment($docComment)) {
            return [];
        }

        $methodName = $methodReflection->getName();
        if (!$classReflection->hasMethod($methodName)) {
            return [];
        }

        $parentClass = $classReflection->getParentClass();
        while($parentClass !== null) {
            // method is overridding a parent method, which might be called from the base-class
            if ($parentClass->hasMethod($methodName)) {
                return [];
            }

            $parentClass = $parentClass->getParentClass();
        }

        return [RuleErrorBuilder::message(
            sprintf(self::ERROR_MESSAGE, $methodName)
        )->build()];
    }

}
