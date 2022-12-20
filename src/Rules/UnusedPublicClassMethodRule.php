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
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Twig\PossibleTwigMethodCallsProvider;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\UnusedPublicClassMethodRuleTest
 *
 * @see \TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector
 * @see \TomasVotruba\UnusedPublic\Collectors\MethodCallCollector
 */
final class UnusedPublicClassMethodRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Class method "%s()" is never used outside of its class';

    /**
     * @var string
     */
    public const TIP_MESSAGE = 'Either reduce the methods visibility or annotate it or its class with @api.';
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

    public function __construct(Configuration $configuration, PossibleTwigMethodCallsProvider $possibleTwigMethodCallsProvider)
    {
        $this->configuration = $configuration;
        $this->possibleTwigMethodCallsProvider = $possibleTwigMethodCallsProvider;
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
                    ->tip(self::TIP_MESSAGE)
                    ->build();
            }
        }

        return $ruleErrors;
    }

    /**
     * @param mixed[] $usedClassMethods
     * @param string[] $twigMethods
     */
    private function isUsedClassMethod(
        string $className,
        string $methodName,
        array $usedClassMethods,
        array $twigMethods
    ): bool {
        if ($twigMethods !== [] && in_array($methodName, $twigMethods, true)) {
            return true;
        }

        $publicMethodReference = $className . '::' . $methodName;
        $usedClassMethods = Arrays::flatten($usedClassMethods);

        return in_array($publicMethodReference, $usedClassMethods, true);
    }
}
