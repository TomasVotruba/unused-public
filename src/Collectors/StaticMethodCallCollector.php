<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<StaticCall, array<string>|null>
 */
final class StaticMethodCallCollector implements Collector
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    /**
     * @param StaticCall $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedMethodEnabled()) {
            return null;
        }

        if ($node->name instanceof Expr) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        return [$node->class->toString() . '::' . $node->name->toString()];
    }
}
