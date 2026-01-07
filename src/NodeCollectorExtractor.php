<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PHPStan\Node\CollectedDataNode;
use TomasVotruba\UnusedPublic\CollectorMapper\MethodCallCollectorMapper;
use TomasVotruba\UnusedPublic\Collectors\Callable_\AttributeCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\Callable_\CallableTypeCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\MethodCall\MethodCallCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallableCollector;
use TomasVotruba\UnusedPublic\Collectors\StaticCall\StaticMethodCallCollector;
use TomasVotruba\UnusedPublic\ValueObject\LocalAndExternalMethodCallReferences;

final readonly class NodeCollectorExtractor
{
    public function __construct(
        private MethodCallCollectorMapper $methodCallCollectorMapper
    ) {
    }

    public function extractLocalAndExternalMethodCallReferences(
        CollectedDataNode $collectedDataNode
    ): LocalAndExternalMethodCallReferences {
        $collectedDatas = $this->extractCollectedDatas($collectedDataNode);
        return $this->methodCallCollectorMapper->mapToLocalAndExternal($collectedDatas);
    }

    /**
     * @return string[]
     */
    public function extractMethodCallReferences(CollectedDataNode $collectedDataNode): array
    {
        $collectedDatas = $this->extractCollectedDatas($collectedDataNode);
        return $this->methodCallCollectorMapper->mapToMethodCallReferences($collectedDatas);
    }

    /**
     * @return array<int, array<string, list<(non-empty-array<string> | null)>>>
     */
    private function extractCollectedDatas(CollectedDataNode $collectedDataNode): array
    {
        return [
            $collectedDataNode->get(MethodCallCollector::class),
            $collectedDataNode->get(MethodCallableCollector::class),
            $collectedDataNode->get(StaticMethodCallCollector::class),
            $collectedDataNode->get(StaticMethodCallableCollector::class),
            $collectedDataNode->get(AttributeCallableCollector::class),
            $collectedDataNode->get(CallableTypeCollector::class),
        ];
    }
}
