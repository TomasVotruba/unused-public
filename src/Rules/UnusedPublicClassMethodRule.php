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
    public const ERROR_MESSAGE = 'Class method "%s()" is never used outside of its class';

    public function __construct(
        private readonly Configuration $configuration,
        private readonly PossibleTwigMethodCallsProvider $possibleTwigMethodCallsProvider,
        private readonly UsedMethodAnalyzer $usedMethodAnalyzer,
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

        $twigMethodNames = $this->possibleTwigMethodCallsProvider->provide();

        $methodCallCollector = $node->get(MethodCallCollector::class);
        $staticMethodCallCollector = $node->get(StaticMethodCallCollector::class);

        $methodCallCollector = array_merge_recursive($methodCallCollector, $staticMethodCallCollector);

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);

        $ruleErrors = [];

        foreach ($publicClassMethodCollector as $filePath => $declarations) {
            foreach ($declarations as [$className, $methodName, $line]) {
                if ($this->isUsedClassMethod($className, $methodName, $methodCallCollector, $twigMethodNames)) {
                    continue;
                }

                /** @var string $methodName */
                $errorMessage = sprintf(self::ERROR_MESSAGE, $methodName);

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
     * @param mixed[] $usedClassMethods
     * @param string[] $twigMethodNames
     */
    private function isUsedClassMethod(
        string $className,
        string $methodName,
        array $usedClassMethods,
        array $twigMethodNames
    ): bool {
        if ($this->usedMethodAnalyzer->isUsedInTwig($methodName, $twigMethodNames)) {
            return true;
        }

        $publicMethodReference = $className . '::' . $methodName;
        $usedClassMethods = Arrays::flatten($usedClassMethods);

        return in_array($publicMethodReference, $usedClassMethods, true);
    }
}
