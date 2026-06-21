<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Templates;

use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\Template\BladeRegex;
use TomasVotruba\UnusedPublic\Enum\Template\TwigRegex;

final class TemplateMethodCallsProvider
{
    /**
     * @readonly
     */
    private Configuration $configuration;

    /**
     * @readonly
     */
    private TemplateRegexFinder $templateRegexFinder;

    public function __construct(Configuration $configuration, TemplateRegexFinder $templateRegexFinder)
    {
        $this->configuration = $configuration;
        $this->templateRegexFinder = $templateRegexFinder;
    }

    /**
     * @return string[]
     */
    public function provideBladeMethodCalls(): array
    {
        return $this->templateRegexFinder->find(
            $this->configuration->getTemplatePaths(),
            'blade.php',
            [BladeRegex::INNER_REGEX, BladeRegex::TAG_REGEX],
            BladeRegex::METHOD_CALL_REGEX
        );
    }

    /**
     * @return string[]
     */
    public function provideTwigMethodCalls(): array
    {
        return $this->templateRegexFinder->find(
            $this->configuration->getTemplatePaths(),
            'twig',
            [TwigRegex::INNER_REGEX],
            TwigRegex::METHOD_CALL_REGEX
        );
    }
}
