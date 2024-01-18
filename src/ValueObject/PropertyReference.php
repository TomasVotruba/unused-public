<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

use Stringable;
use TomasVotruba\UnusedPublic\Enum\ReferenceMarker;

final class PropertyReference implements Stringable
{
    public static function fromString(string $propertyReference): self
    {
        $isTest = false;
        if (str_contains($propertyReference, ReferenceMarker::TEST)) {
            $isTest = true;
            $propertyReference = str_replace(ReferenceMarker::TEST, '', $propertyReference);
        }

        [$class, $property] = explode('::', $propertyReference);

        return new self($class, $property, $isTest);
    }

    public function __construct(
        private readonly string $class,
        private readonly string $property,
        private readonly bool $isTest,
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function __toString(): string
    {
        $className = $this->getClass();
        $propertyName = $this->getProperty();

        $propertyReference = $className . '::' . $propertyName;
        if ($this->isTest()) {
            $propertyReference = ReferenceMarker::TEST . $propertyReference;
        }

        return $propertyReference;
    }
}
