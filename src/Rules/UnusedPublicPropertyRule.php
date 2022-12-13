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
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyFetchCollector;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\UnusedPublicPropertyRuleTest
 */
final class UnusedPublicPropertyRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Property "%s()" is never used outside of its class';

    /**
     * @var string
     */
    public const TIP_MESSAGE = 'Either reduce the property visibility or annotate it or its class with @api.';

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
        $publicPropertyCollector = $node->get(PublicPropertyCollector::class);
        $publicPropertyFetchCollector = $node->get(PublicPropertyFetchCollector::class);

        $ruleErrors = [];

        foreach ($publicPropertyCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $propertyName, $line]) {
                    if ($this->isPropertyUsed($className, $propertyName, $publicPropertyFetchCollector)) {
                        continue;
                    }

                    /** @var string $propertyName */
                    $errorMessage = sprintf(self::ERROR_MESSAGE, $propertyName);

                    $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                        ->file($filePath)
                        ->line($line)
                        ->tip(self::TIP_MESSAGE)
                        ->build();
                }
            }
        }

        return $ruleErrors;
    }

    /**
     * @param mixed[] $usedProperties
     */
    private function isPropertyUsed(string $className, string $constantName, array $usedProperties): bool
    {
        $publicPropertyReference = $className . '::' . $constantName;
        $usedProperties = Arrays::flatten($usedProperties);

        return in_array($publicPropertyReference, $usedProperties, true);
    }
}
