<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

use Stringable;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;

final class ConstantReference implements Stringable
{
    public static function fromString(string $constantReference): self
    {
        $isTest = false;
        if (str_contains($constantReference, ReferenceMarker::TEST)) {
            $isTest = true;
            $constantReference = str_replace(ReferenceMarker::TEST, '', $constantReference);
        }

        [$class, $constant] = explode('::', $constantReference);

        return new self($class, $constant, $isTest);
    }

    public function __construct(
        private readonly string $class,
        private readonly string $constant,
        private readonly bool $isTest,
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getConstant(): string
    {
        return $this->constant;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function __toString(): string
    {
        $className = $this->getClass();
        $constantName = $this->getConstant();

        $constantReference = $className . '::' . $constantName;
        if ($this->isTest()) {
            $constantReference = ReferenceMarker::TEST . $constantReference;
        }

        return $constantReference;
    }
}
