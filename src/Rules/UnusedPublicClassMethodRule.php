<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\CollectorMapper\MethodCallCollectorMapper;
use TomasVotruba\UnusedPublic\Collectors\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider;
use TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\UnusedPublicClassMethodRuleTest
 */
final class UnusedPublicClassMethodRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public method "%s::%s()" is never used';

    public function __construct(
        private readonly Configuration $configuration,
        private readonly TemplateMethodCallsProvider $templateMethodCallsProvider,
        private readonly UsedMethodAnalyzer $usedMethodAnalyzer,
        private readonly MethodCallCollectorMapper $methodCallCollectorMapper,
    ) {
    }

    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * @param CollectedDataNode $node
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isUnusedMethodEnabled()) {
            return [];
        }

        $twigMethodNames = $this->templateMethodCallsProvider->provideTwigMethodCalls();
        $bladeMethodNames = $this->templateMethodCallsProvider->provideBladeMethodCalls();

        $completeMethodCallReferences = $this->methodCallCollectorMapper->mapToMethodCallReferences(
            $node->get(MethodCallCollector::class),
            $node->get(StaticMethodCallCollector::class),
            $node->get(AttributeCallableCollector::class)
        );

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);

        $ruleErrors = [];

        foreach ($publicClassMethodCollector as $filePath => $declarations) {
            foreach ($declarations as [$className, $methodName, $line]) {
                if ($this->isUsedClassMethod(
                    $className,
                    $methodName,
                    $completeMethodCallReferences,
                    $twigMethodNames,
                    $bladeMethodNames
                )) {
                    continue;
                }

                /** @var string $methodName */
                $errorMessage = sprintf(self::ERROR_MESSAGE, $className, $methodName);

                $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                    ->file($filePath)
                    ->line($line)
                    ->tip(RuleTips::SOLUTION_MESSAGE)
                    ->build();
            }
        }

        return $ruleErrors;
    }

    /**
     * @param string[] $completeMethodCallReferences
     * @param string[] $twigMethodNames
     * @param string[] $bladeMethodNames
     */
    private function isUsedClassMethod(
        string $className,
        string $methodName,
        array $completeMethodCallReferences,
        array $twigMethodNames,
        array $bladeMethodNames
    ): bool {
        if ($this->usedMethodAnalyzer->isUsedInTwig($methodName, $twigMethodNames)) {
            return true;
        }

        if (in_array($methodName, $bladeMethodNames, true)) {
            return true;
        }

        // php method calls are case-insensitive
        $lowerCompleteMethodCallReferences = array_map(
            fn(string $item): string => strtolower($item),
            $completeMethodCallReferences
        );

        $methodReference = $className . '::' . $methodName;
        return in_array(strtolower($methodReference), $lowerCompleteMethodCallReferences, true);
    }
}
