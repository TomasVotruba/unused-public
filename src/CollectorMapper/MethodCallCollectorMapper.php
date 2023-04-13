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
     * @param array<string, mixed[]> $attributeCallReferencesByFile
     * @param array<string, mixed[]> $callbackReferencesByFile
     * @return string[]
     */
    public function mapToMethodCallReferences(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
        array $attributeCallReferencesByFile,
        array $callbackReferencesByFile,
    ): array {
        $methodCallReferences = $this->mergeAndFlatten(
            $methodCallReferencesByFile,
            $staticCallReferencesByFile,
            $attributeCallReferencesByFile,
            $callbackReferencesByFile
        );

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
     * @param array<string, mixed[]> $attributeCallReferencesByFile
     */
    public function mapToLocalAndExternal(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
        array $attributeCallReferencesByFile
    ): LocalAndExternalMethodCallReferences {
        $methodCallReferences = $this->mergeAndFlatten(
            $methodCallReferencesByFile,
            $staticCallReferencesByFile,
            $attributeCallReferencesByFile,
            []
        );

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
     * @param array<string, mixed[]> $methodCallReferencesByFile
     * @param array<string, mixed[]> $staticCallReferencesByFile
     * @param array<string, mixed[]> $attributeCallReferencesByFile
     * @param array<string, mixed[]> $callbackReferencesByFile
     * @return string[]
     */
    private function mergeAndFlatten(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
        array $attributeCallReferencesByFile,
        array $callbackReferencesByFile
    ): array {
        return array_merge(
            Arrays::flatten($methodCallReferencesByFile),
            Arrays::flatten($staticCallReferencesByFile),
            Arrays::flatten($attributeCallReferencesByFile),
            Arrays::flatten($callbackReferencesByFile),
        );
    }
}
