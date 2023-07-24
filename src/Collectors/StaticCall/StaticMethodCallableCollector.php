<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\StaticMethodCallableNode;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<StaticMethodCallableNode, array<string>|null>
 */
final class StaticMethodCallableCollector implements Collector
{
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
        return StaticMethodCallableNode::class;
    }

    /**
     * @param StaticMethodCallableNode $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        if (! $node->getName() instanceof Identifier) {
            return null;
        }

        if (! $node->getClass() instanceof Name) {
            return null;
        }

        return [$node->getClass()->toString() . '::' . $node->getName()->toString()];
    }
}
