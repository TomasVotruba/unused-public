<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\StaticMethodCallableNode;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<StaticMethodCallableNode, non-empty-array<string>|null>
 */
final readonly class StaticMethodCallableCollector implements Collector
{
    public function __construct(
        private Configuration $configuration,
        private ClassTypeDetector $classTypeDetector,
    ) {
    }

    public function getNodeType(): string
    {
        return StaticMethodCallableNode::class;
    }

    /**
     * @param StaticMethodCallableNode $node
     * @return non-empty-array<string>|null
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

        // skip calls in tests, as they are not used in production
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        return [$node->getClass()->toString() . '::' . $node->getName()->toString()];
    }
}
