<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

/**
 * @inspired by https://github.com/phpstan/phpstan-symfony/blob/1.2.x/src/Symfony/Configuration.php
 */
final class Configuration
{
    /**
     * @var array<string, mixed>
     * @readonly
     */
    private $parameters;
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function isUnusedMethodEnabled(): bool
    {
        return $this->parameters['methods'] ?? false;
    }

    public function isUnusedStaticPropertyEnabled(): bool
    {
        return $this->parameters['static_properties'] ?? false;
    }

    public function isUnusedPropertyEnabled(): bool
    {
        return $this->parameters['properties'] ?? false;
    }

    public function isUnusedConstantsEnabled(): bool
    {
        return $this->parameters['constants'] ?? false;
    }
}
