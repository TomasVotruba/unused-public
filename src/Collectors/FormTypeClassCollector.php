<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * Match Symfony data_class element in forms types, as those use magic setters/getters
 * @implements Collector<ArrayItem, non-empty-array<string>|null>
 */
final readonly class FormTypeClassCollector implements Collector
{
    public function __construct(
        private Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return ArrayItem::class;
    }

    /**
     * @param ArrayItem $node
     * @return non-empty-array<string>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        if (! $node->key instanceof String_) {
            return null;
        }

        if ($node->key->value !== 'data_class') {
            return null;
        }

        $valueType = $scope->getType($node->value);
        if (count($valueType->getConstantStrings()) !== 1) {
            return null;
        }

        return [$valueType->getConstantStrings()[0]->getValue()];
    }
}
