<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicPropertyFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicStaticPropertyFetchCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Utils\Arrays;
use TomasVotruba\UnusedPublic\ValueObject\PropertyReference;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\UnusedPublicPropertyRuleTest
 */
final class UnusedPublicPropertyRule implements Rule
{
    /**
     * @var string
     *
     * @api
     */
    public const ERROR_MESSAGE = 'Public property "%s::$%s" is never used';

    public function __construct(
        private readonly Configuration $configuration
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
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return [];
        }

        $publicPropertyCollector = $node->get(PublicPropertyCollector::class);
        $publicPropertyFetchCollector = $node->get(PublicPropertyFetchCollector::class);
        $publicStaticPropertyFetchCollector = $node->get(PublicStaticPropertyFetchCollector::class);

        $usedPropertiesFlattened = [
            ...Arrays::flatten($publicPropertyFetchCollector),
            ...Arrays::flatten($publicStaticPropertyFetchCollector),
        ];
        $usedProperties = array_map(static function (string $propertyReference): PropertyReference {
            return PropertyReference::fromString($propertyReference);
        }, $usedPropertiesFlattened);

        $ruleErrors = [];

        foreach ($publicPropertyCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $propertyName, $line, $isInternal]) {
                    if ($this->isPropertyUsed($className, $propertyName, $usedProperties, $isInternal)) {
                        continue;
                    }

                    /** @var string $propertyName */
                    $errorMessage = sprintf(self::ERROR_MESSAGE, $className, $propertyName);

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
     * @param PropertyReference[] $usedProperties
     */
    private function isPropertyUsed(
        string $className,
        string $constantName,
        array $usedProperties,
        bool $isInternal,
    ): bool {
        $publicPropertyReference = $className . '::' . $constantName;

        foreach ($usedProperties as $usedProperty) {
            // skip calls in tests, if they are not internal
            if (! $isInternal && $usedProperty->isTest()) {
                continue;
            }

            $usedPropertyReference = $usedProperty->getClass() . '::' . $usedProperty->getProperty();
            if ($usedPropertyReference === $publicPropertyReference) {
                return true;
            }
        }

        return false;
    }
}
