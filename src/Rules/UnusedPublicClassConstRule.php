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
use TomasVotruba\UnusedPublic\Collectors\ClassConstFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassLikeConstCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\UnusedPublicClassConstRuleTest
 */
final class UnusedPublicClassConstRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public constant "%s::%s" is never used';

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
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
        if (! $this->configuration->isUnusedConstantsEnabled()) {
            return [];
        }

        $classConstFetchCollector = $node->get(ClassConstFetchCollector::class);
        $publicClassLikeConstCollector = $node->get(PublicClassLikeConstCollector::class);

        $ruleErrors = [];

        foreach ($publicClassLikeConstCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $constantName, $line]) {
                    if ($this->isClassConstantUsed($className, $constantName, $classConstFetchCollector)) {
                        continue;
                    }

                    /** @var string $constantName */
                    $errorMessage = sprintf(self::ERROR_MESSAGE, $className, $constantName);

                    $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                        ->file($filePath)
                        ->line($line)
                        ->tip(RuleTips::SOLUTION_MESSAGE)
                        ->build();
                }
            }
        }

        return $ruleErrors;
    }

    /**
     * @param mixed[] $usedConstFetches
     */
    private function isClassConstantUsed(string $className, string $constantName, array $usedConstFetches): bool
    {
        $publicConstantReference = $className . '::' . $constantName;

        $usedConstFetches = Arrays::flatten($usedConstFetches);
        return in_array($publicConstantReference, $usedConstFetches, true);
    }
}
