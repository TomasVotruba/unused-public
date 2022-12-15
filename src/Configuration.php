<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

/**
 * @inspired by https://github.com/phpstan/phpstan-symfony/blob/1.2.x/src/Symfony/Configuration.php
 */
final class Configuration
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private array $parameters
    ) {
    }

    public function isUnusedMethodEnabled(): bool
    {
        return $this->parameters['methods'] ?? false;
    }
}
