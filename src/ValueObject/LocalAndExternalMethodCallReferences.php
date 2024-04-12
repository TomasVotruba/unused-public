<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

final readonly class LocalAndExternalMethodCallReferences
{
    /**
     * @param string[] $localMethodCallReferences
     * @param string[] $externalMethodCallReferences
     */
    public function __construct(
        private array $localMethodCallReferences,
        private array $externalMethodCallReferences,
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
