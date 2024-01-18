<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\PropertyReference\ParentPropertyReferenceResolver;

/**
 * @implements Collector<StaticPropertyFetch, string[]>
 */
final class PublicStaticPropertyFetchCollector implements Collector
{
    public function __construct(
        private readonly ParentPropertyReferenceResolver $parentPropertyReferenceResolver,
        private readonly Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return StaticPropertyFetch::class;
    }

    /**
     * @param StaticPropertyFetch $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->isUnusedPropertyEnabled()) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        if ($node->class->toString() === 'self') {
            // self fetch is allowed
            return null;
        }

        $className = $node->class->toString();
        $propertyName = $node->name->toString();

        $propertyReferences = [$className . '::' . $propertyName];
        $parentPropertyReferences = $this->parentPropertyReferenceResolver->findParentPropertyReferences($className, $propertyName);
        $propertyReferences = [...$propertyReferences, ...$parentPropertyReferences];

        return $propertyReferences;
    }
}
