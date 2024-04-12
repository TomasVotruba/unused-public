<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\Collectors\PublicClassMethodCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\NodeCollectorExtractor;
use TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsProvider;
use TomasVotruba\UnusedPublic\Templates\UsedMethodAnalyzer;
use TomasVotruba\UnusedPublic\Utils\Strings;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\LocalOnlyPublicClassMethodRuleTest
 */
final readonly class LocalOnlyPublicClassMethodRule implements Rule
{
    /**
     * @var string
     *
     * @api
     */
    public const ERROR_MESSAGE = 'Public method "%s::%s()" is used only locally and should be turned protected/private';

    public function __construct(
        private Configuration $configuration,
        private UsedMethodAnalyzer $usedMethodAnalyzer,
        private TemplateMethodCallsProvider $templateMethodCallsProvider,
        private NodeCollectorExtractor $nodeCollectorExtractor,
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

        $localAndExternalMethodCallReferences = $this->nodeCollectorExtractor->extractLocalAndExternalMethodCallReferences(
            $node
        );

        // php method calls are case-insensitive
        $lowerExternalRefs = Strings::lowercase(
            $localAndExternalMethodCallReferences->getExternalMethodCallReferences()
        );

        $lowerLocalRefs = Strings::lowercase(
            $localAndExternalMethodCallReferences->getLocalMethodCallReferences()
        );

        $ruleErrors = [];

        $publicClassMethodCollector = $node->get(PublicClassMethodCollector::class);
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

        $publicMethodReference = strtolower($className . '::' . $methodName);

        if (in_array($publicMethodReference, $lowerExternalRefs, true)) {
            return false;
        }

        return in_array($publicMethodReference, $lowerLocalRefs, true);
    }
}
