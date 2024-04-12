<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use Nette\Utils\Arrays;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\Collectors\FormTypeClassCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\NodeCollectorExtractor;
use TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider;
use TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer;
use TomasVotruba\UnusedPublic\Utils\Strings;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\UnusedPublicClassMethodRuleTest
 */
final class UnusedPublicClassMethodRule implements Rule
{
    /**
     * @var string
     *
     * @api
     */
    public const ERROR_MESSAGE = 'Public method "%s::%s()" is never used';

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider
     */
    private $templateMethodCallsProvider;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer
     */
    private $usedMethodAnalyzer;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\NodeCollectorExtractor
     */
    private $nodeCollectorExtractor;

    public function __construct(Configuration $configuration, TemplateMethodCallsProvider $templateMethodCallsProvider, UsedMethodAnalyzer $usedMethodAnalyzer, NodeCollectorExtractor $nodeCollectorExtractor)
    {
        $this->configuration = $configuration;
        $this->templateMethodCallsProvider = $templateMethodCallsProvider;
        $this->usedMethodAnalyzer = $usedMethodAnalyzer;
        $this->nodeCollectorExtractor = $nodeCollectorExtractor;
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

        $completeMethodCallReferences = $this->nodeCollectorExtractor->extractMethodCallReferences($node);
        $formTypeClasses = Arrays::flatten($node->get(FormTypeClassCollector::class));

        // php method calls are case-insensitive
        $lowerCompleteMethodCallReferences = Strings::lowercase($completeMethodCallReferences);

        $ruleErrors = [];

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);
        foreach ($publicClassMethodCollector as $filePath => $declarations) {
            foreach ($declarations as [$className, $methodName, $line]) {
                if (in_array($className, $formTypeClasses, true)) {
                    continue;
                }

                if ($this->isUsedClassMethod(
                    $className,
                    $methodName,
                    $lowerCompleteMethodCallReferences,
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
     * @param string[] $lowerCompleteMethodCallReferences
     * @param string[] $twigMethodNames
     * @param string[] $bladeMethodNames
     */
    private function isUsedClassMethod(
        string $className,
        string $methodName,
        array $lowerCompleteMethodCallReferences,
        array $twigMethodNames,
        array $bladeMethodNames
    ): bool {
        if ($this->usedMethodAnalyzer->isUsedInTwig($methodName, $twigMethodNames)) {
            return true;
        }

        if (in_array($methodName, $bladeMethodNames, true)) {
            return true;
        }

        $methodReference = $className . '::' . $methodName;
        return in_array(strtolower($methodReference), $lowerCompleteMethodCallReferences, true);
    }
}
