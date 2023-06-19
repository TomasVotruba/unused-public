<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Type\Constant\ConstantStringType;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * Match Symfony data_class element in forms types, as those use magic setters/getters
 * @implements Collector<ArrayItem, array<string>|null>
 */
final class FormTypeClassCollector implements Collector
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return ArrayItem::class;
    }

    /**
     * @param ArrayItem $node
     * @return string[]|null
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
        if (! $valueType instanceof ConstantStringType) {
            return null;
        }

        return [$valueType->getValue()];
    }
}
