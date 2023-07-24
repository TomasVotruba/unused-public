<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\CollectorMapper;

use Nette\Utils\Arrays;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;
use TomasVotruba\UnusedPublic\ValueObject\LocalAndExternalMethodCallReferences;

final class MethodCallCollectorMapper
{
    /**
     * @param array<string, mixed[]> $methodCallReferencesByFile
     * @param array<string, mixed[]> $staticCallReferencesByFile
     * @param array<string, mixed[]> $staticCallableReferencesByFile
     * @param array<string, mixed[]> $attributeCallReferencesByFile
     * @param array<string, mixed[]> $callbackReferencesByFile
     * @return string[]
     */
    public function mapToMethodCallReferences(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
        array $staticCallableReferencesByFile,
        array $attributeCallReferencesByFile,
        array $callbackReferencesByFile,
    ): array {
        $methodCallReferences = $this->mergeAndFlatten([
            $methodCallReferencesByFile,
            $staticCallReferencesByFile,
            $staticCallableReferencesByFile,
            $attributeCallReferencesByFile,
            $callbackReferencesByFile,
        ]);

        // remove ReferenceMaker::LOCAL prefix
        return array_map(static function (string $methodCallReference): string {
            if (str_starts_with($methodCallReference, ReferenceMarker::LOCAL)) {
                return substr($methodCallReference, strlen(ReferenceMarker::LOCAL));
            }

            return $methodCallReference;
        }, $methodCallReferences);
    }

    /**
     * @param array<string, mixed[]> $methodCallReferencesByFile
     * @param array<string, mixed[]> $staticCallReferencesByFile
     * @param array<string, mixed[]> $staticCallableReferencesByFile
     * @param array<string, mixed[]> $attributeCallReferencesByFile
     * @param array<string, mixed[]> $callbackReferencesByFile
     */
    public function mapToLocalAndExternal(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
        array $staticCallableReferencesByFile,
        array $attributeCallReferencesByFile,
        array $callbackReferencesByFile
    ): LocalAndExternalMethodCallReferences {
        $methodCallReferences = $this->mergeAndFlatten([
            $methodCallReferencesByFile,
            $staticCallReferencesByFile,
            $staticCallableReferencesByFile,
            $attributeCallReferencesByFile,
            $callbackReferencesByFile,
        ]);

        $localMethodCallReferences = [];
        $externalMethodCallReferences = [];

        foreach ($methodCallReferences as $methodCallReference) {
            if (str_starts_with($methodCallReference, ReferenceMarker::LOCAL)) {
                $localMethodCallReferences[] = substr($methodCallReference, strlen(ReferenceMarker::LOCAL));
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
