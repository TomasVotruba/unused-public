<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\CollectorMapper;

use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;
use TomasVotruba\UnusedPublic\Utils\Arrays;
use TomasVotruba\UnusedPublic\ValueObject\LocalAndExternalMethodCallReferences;
use TomasVotruba\UnusedPublic\ValueObject\MethodCallReference;

final class MethodCallCollectorMapper
{
    /**
     * @param array<array<string, mixed[]>> $nestedReferencesByFiles
     * @return MethodCallReference[]
     */
    public function mapToMethodCallReferences(array $nestedReferencesByFiles): array
    {
        $methodCallReferences = $this->mergeAndFlatten($nestedReferencesByFiles);

        return array_map(static function (string $methodCallReference): MethodCallReference {
            return MethodCallReference::fromString($methodCallReference);
        }, $methodCallReferences);
    }

    /**
     * @param array<array<string, mixed[]>> $nestedReferencesByFiles
     */
    public function mapToLocalAndExternal(array $nestedReferencesByFiles): LocalAndExternalMethodCallReferences
    {
        $methodCallReferences = $this->mergeAndFlatten($nestedReferencesByFiles);

        $localMethodCallReferences = [];
        $externalMethodCallReferences = [];

        foreach ($methodCallReferences as $methodCallReference) {
            if (str_contains($methodCallReference, ReferenceMarker::LOCAL)) {
                $localMethodCallReferences[] = str_replace(ReferenceMarker::LOCAL, '', $methodCallReference);
            } else {
                $externalMethodCallReferences[] = $methodCallReference;
            }
        }

        return new LocalAndExternalMethodCallReferences($localMethodCallReferences, $externalMethodCallReferences);
    }

    /**
     * @param array<array<string, mixed[]>> $nestedReferencesByFiles
     * @return string[]
     */
    private function mergeAndFlatten(array $nestedReferencesByFiles): array
    {
        $flattenReferences = [];

        foreach ($nestedReferencesByFiles as $nestedReferences) {
            $flattenReferences = array_merge($flattenReferences, Arrays::flatten($nestedReferences));
        }

        return $flattenReferences;
    }
}
