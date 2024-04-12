<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

final readonly class MethodCallReference
{
    public function __construct(
        private string $class,
        private string $method,
        private bool $isLocal
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function isLocal(): bool
    {
        return $this->isLocal;
    }
}
