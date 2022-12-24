<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

final class MethodCallReference
{
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly bool $isLocal
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
