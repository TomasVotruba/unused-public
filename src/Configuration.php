<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use Webmozart\Assert\Assert;

/**
 * @inspired by https://github.com/phpstan/phpstan-symfony/blob/1.2.x/src/Symfony/Configuration.php
 */
final readonly class Configuration
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

    public function shouldCollectMethods(): bool
    {
        if ($this->parameters['methods']) {
            return true;
        }

        return $this->parameters['local_methods'];
    }

    public function isLocalMethodEnabled(): bool
    {
        return $this->parameters['local_methods'] ?? false;
    }

    public function isUnusedPropertyEnabled(): bool
    {
        return $this->parameters['properties'] ?? false;
    }

    public function isUnusedConstantsEnabled(): bool
    {
        return $this->parameters['constants'] ?? false;
    }

    /**
     * @return string[]
     */
    public function getTemplatePaths(): array
    {
        $templatePaths = $this->parameters['template_paths'];

        Assert::allDirectory($templatePaths);
        Assert::allFileExists($templatePaths);

        return $templatePaths;
    }
}
