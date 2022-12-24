<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

final class LocalAndExternalMethodCallReferences
{
    /**
     * @param string[] $localMethodCallReferences
     * @param string[] $externalMethodCallReferences
     */
    public function __construct(
        private readonly array $localMethodCallReferences,
        private readonly array $externalMethodCallReferences,
    ) {
    }

    /**
     * @return string[]
     */
    public function getLocalMethodCallReferences(): array
    {
        return $this->localMethodCallReferences;
    }

    /**
     * @return string[]
     */
    public function getExternalMethodCallReferences(): array
    {
        return $this->externalMethodCallReferences;
    }
}
