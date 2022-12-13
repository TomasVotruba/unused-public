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
use TomasVotruba\UnusedPublic\Collectors\PublicStaticPropertyCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicStaticPropertyFetchCollector;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicStaticPropertyRule\UnusedPublicStaticPropertyRuleTest
 */
final class UnusedPublicStaticPropertyRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public property "$%s" is never used outside of its class';

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
        $staticStaticPropertyFetchCollector = $node->get(PublicStaticPropertyFetchCollector::class);
        $publicStaticPropertyCollector = $node->get(PublicStaticPropertyCollector::class);

        $ruleErrors = [];

        foreach ($publicStaticPropertyCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $propertyName, $line]) {
                    if ($this->isPublicStaticPropertyUsed(
                        $className,
                        $propertyName,
                        $staticStaticPropertyFetchCollector
                    )) {
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
    private function isPublicStaticPropertyUsed(string $className, string $propertyName, array $usedProperties): bool
    {
        $publicPropertyReference = $className . '::' . $propertyName;

        $usedProperties = Arrays::flatten($usedProperties);
        return in_array($publicPropertyReference, $usedProperties, true);
    }
}
