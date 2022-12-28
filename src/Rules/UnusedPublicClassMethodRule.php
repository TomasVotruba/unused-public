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
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Twig\PossibleTwigMethodCallsProvider;
use TomasVotruba\UnusedPublic\Twig\UsedMethodAnalyzer;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\UnusedPublicClassMethodRuleTest
 */
final class UnusedPublicClassMethodRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public method "%s::%s()" is never used';
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Twig\PossibleTwigMethodCallsProvider
     */
    private $possibleTwigMethodCallsProvider;
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Twig\UsedMethodAnalyzer
     */
    private $usedMethodAnalyzer;
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\CollectorMapper\MethodCallCollectorMapper
     */
    private $methodCallCollectorMapper;
    public function __construct(Configuration $configuration, PossibleTwigMethodCallsProvider $possibleTwigMethodCallsProvider, UsedMethodAnalyzer $usedMethodAnalyzer, MethodCallCollectorMapper $methodCallCollectorMapper)
    {
        $this->configuration = $configuration;
        $this->possibleTwigMethodCallsProvider = $possibleTwigMethodCallsProvider;
        $this->usedMethodAnalyzer = $usedMethodAnalyzer;
        $this->methodCallCollectorMapper = $methodCallCollectorMapper;
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

        $twigMethodNames = $this->possibleTwigMethodCallsProvider->provide();

        $completeMethodCallReferences = $this->methodCallCollectorMapper->mapToMethodCallReferences(
            $node->get(MethodCallCollector::class),
            $node->get(StaticMethodCallCollector::class)
        );

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);

        $ruleErrors = [];

        foreach ($publicClassMethodCollector as $filePath => $declarations) {
            foreach ($declarations as [$className, $methodName, $line]) {
                if ($this->isUsedClassMethod(
                    $className,
                    $methodName,
                    $completeMethodCallReferences,
                    $twigMethodNames
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
     * @param string[] $twigMethodNames
     * @param string[] $completeMethodCallReferences
     */
    private function isUsedClassMethod(
        string $className,
        string $methodName,
        array $completeMethodCallReferences,
        array $twigMethodNames
    ): bool {
        if ($this->usedMethodAnalyzer->isUsedInTwig($methodName, $twigMethodNames)) {
            return true;
        }

        $methodReference = $className . '::' . $methodName;
        return in_array($methodReference, $completeMethodCallReferences, true);
    }
}
