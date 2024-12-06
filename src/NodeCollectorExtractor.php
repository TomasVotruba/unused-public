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

final class NodeCollectorExtractor
{
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\CollectorMapper\MethodCallCollectorMapper
     */
    private $methodCallCollectorMapper;

    public function __construct(MethodCallCollectorMapper $methodCallCollectorMapper)
    {
        $this->methodCallCollectorMapper = $methodCallCollectorMapper;
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
     * @return mixed[]
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
