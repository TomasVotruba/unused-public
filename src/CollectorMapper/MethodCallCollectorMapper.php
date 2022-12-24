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
     * @return string[]
     */
    public function mapToMethodCallReferences(
        array $methodCallReferencesByFile,
        array $staticCallReferencesByFile,
    ): array {
        $completeMethodCallReferences = array_merge_recursive($methodCallReferencesByFile, $staticCallReferencesByFile);

        return Arrays::flatten($completeMethodCallReferences);
    }

    /**
     * @param string[] $methodCallReferences
     */
    public function mapToLocalAndExternal(array $methodCallReferences): LocalAndExternalMethodCallReferences
    {
        // /** @var string[] $methodCallReferences */
        //        $methodCallReferences = Arrays::flatten($methodCallReferences);

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
}
