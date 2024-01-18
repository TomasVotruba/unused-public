<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

/**
 * @implements Collector<StaticCall, array<string>|null>
 */
final class StaticMethodCallCollector implements Collector
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly ClassTypeDetector $classTypeDetector,
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
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        $isTest = $classReflection instanceof ClassReflection && $this->classTypeDetector->isTestClass($classReflection);

        $classMethodCallReference = new MethodCallReference(
            $node->class->toString(),
            $node->name->toString(),
            false,
            $isTest,
        );

        return [(string) $classMethodCallReference];
    }
}
