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
use TomasVotruba\UnusedPublic\Collectors\CallUserFuncCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider;
use TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer;
use TomasVotruba\UnusedPublic\ValueObject\LocalAndExternalMethodCallReferences;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\LocalOnlyPublicClassMethodRuleTest
 */
final class LocalOnlyPublicClassMethodRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public method "%s::%s()" is used only locally and should turned protected/private';

    public function __construct(
        private readonly Configuration $configuration,
        private readonly UsedMethodAnalyzer $usedMethodAnalyzer,
        private readonly TemplateMethodCallsProvider $templateMethodCallsProvider,
        private readonly MethodCallCollectorMapper $methodCallCollectorMapper
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
        if (! $this->configuration->isLocalMethodEnabled()) {
            return [];
        }

        $twigMethodNames = $this->templateMethodCallsProvider->provideTwigMethodCalls();

        $localAndExternalMethodCallReferences = $this->methodCallCollectorMapper->mapToLocalAndExternal(
            $node->get(MethodCallCollector::class),
            $node->get(StaticMethodCallCollector::class),
            $node->get(AttributeCallableCollector::class),
            $node->get(CallUserFuncCollector::class)
        );

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);
        $lowerExternalRefs = array_map(
            fn(string $item): string => strtolower($item),
            $localAndExternalMethodCallReferences->getExternalMethodCallReferences()
        );
        $lowerLocalRefs = array_map(
            fn(string $item): string => strtolower($item),
            $localAndExternalMethodCallReferences->getLocalMethodCallReferences()
        );

        $ruleErrors = [];

        foreach ($publicClassMethodCollector as $filePath => $declarations) {
            foreach ($declarations as [$className, $methodName, $line]) {
                if (! $this->isUsedOnlyLocally(
                    $className,
                    $methodName,
                    $lowerExternalRefs,
                    $lowerLocalRefs,
                    $twigMethodNames
                )) {
                    continue;
                }

                /** @var string $methodName */
                $errorMessage = sprintf(self::ERROR_MESSAGE, $className, $methodName);

                $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                    ->file($filePath)
                    ->line($line)
                    ->tip(RuleTips::NARROW_SCOPE)
                    ->build();
            }
        }

        return $ruleErrors;
    }

    /**
     * @param string[] $lowerExternalRefs
     * @param string[] $lowerLocalRefs
     * @param string[] $twigMethodNames
     */
    private function isUsedOnlyLocally(
        string $className,
        string $methodName,
        array $lowerExternalRefs,
        array $lowerLocalRefs,
        array $twigMethodNames
    ): bool {
        if ($this->usedMethodAnalyzer->isUsedInTwig($methodName, $twigMethodNames)) {
            return true;
        }

        // php method calls are case-insensitive
        $publicMethodReference = strtolower($className . '::' . $methodName);

        if (in_array(
            $publicMethodReference,
            $lowerExternalRefs,
            true
        )) {
            return false;
        }

        return in_array(
            $publicMethodReference,
            $lowerLocalRefs,
            true
        );
    }
}
