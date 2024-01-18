<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

use Stringable;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;

final class MethodCallReference implements Stringable
{
    public static function fromString(string $methodCallReference): self
    {
        $isLocal = false;
        if (str_contains($methodCallReference, ReferenceMarker::LOCAL)) {
            $isLocal = true;
            $methodCallReference = str_replace(ReferenceMarker::LOCAL, '', $methodCallReference);
        }

        $isTest = false;
        if (str_contains($methodCallReference, ReferenceMarker::TEST)) {
            $isTest = true;
            $methodCallReference = str_replace(ReferenceMarker::TEST, '', $methodCallReference);
        }

        [$class, $method] = explode('::', $methodCallReference);

        return new self($class, $method, $isLocal, $isTest);
    }

    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly bool $isLocal,
        private readonly bool $isTest,
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

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function __toString(): string
    {
        $className = $this->getClass();
        $methodName = $this->getMethod();

        $methodCallReference = $className . '::' . $methodName;
        if ($this->isLocal()) {
            $methodCallReference = ReferenceMarker::LOCAL . $methodCallReference;
        }
        if ($this->isTest()) {
            $methodCallReference = ReferenceMarker::TEST . $methodCallReference;
        }

        return $methodCallReference;
    }
}
