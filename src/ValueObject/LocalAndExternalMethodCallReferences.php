<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

final class LocalAndExternalMethodCallReferences
{
    /**
     * @param MethodCallReference[] $localMethodCallReferences
     * @param MethodCallReference[] $externalMethodCallReferences
     */
    public function __construct(
        private readonly array $localMethodCallReferences,
        private readonly array $externalMethodCallReferences,
    ) {
    }

    /**
     * @return MethodCallReference[]
     */
    public function getLocalMethodCallReferences(): array
    {
        return $this->localMethodCallReferences;
    }

    /**
     * @return MethodCallReference[]
     */
    public function getExternalMethodCallReferences(): array
    {
        return $this->externalMethodCallReferences;
    }
}
