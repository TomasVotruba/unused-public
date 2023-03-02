<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Templates;

use TomasVotruba\UnusedPublic\Configuration;

final class TemplateMethodCallsProvider
{
    /**
     * @see https://regex101.com/r/3gLWCt/1
     * @var string
     */
    private const BLADE_INNER_REGEX = '#\{(\{|%)(?<contents>.*?)(\}|%)\}#';

    /**
     * @see https://regex101.com/r/G7zAue/1
     * @var string
     */
    private const BLADE_METHOD_CALL_REGEX = '#\w+(\-\>|::)(?<method_name>\w+)\(\)#';

    /**
     * @see https://regex101.com/r/3gLWCt/1
     * @var string
     */
    private const TWIG_INNER_REGEX = '#\{(\{|%)(?<contents>.*?)(\}|%)\}#';

    /**
     * @see https://regex101.com/r/G7zAue/1
     * @var string
     */
    private const TWIG_METHOD_CALL_REGEX = '#\w+\.(?<method_name>\w+)#';

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Templates\TemplateMethodCallsFinder
     */
    private $templateMethodCallsFinder;

    public function __construct(Configuration $configuration, TemplateMethodCallsFinder $templateMethodCallsFinder)
    {
        $this->configuration = $configuration;
        $this->templateMethodCallsFinder = $templateMethodCallsFinder;
    }

    /**
     * @return string[]
     */
    public function provideBladeMethodCalls(): array
    {
        return $this->templateMethodCallsFinder->find(
            $this->configuration->getTemplatePaths(),
            'blade.php',
            self::BLADE_INNER_REGEX,
            self::BLADE_METHOD_CALL_REGEX
        );
    }

    /**
     * @return string[]
     */
    public function provideTwigMethodCalls(): array
    {
        return $this->templateMethodCallsFinder->find(
            $this->configuration->getTemplatePaths(),
            'twig',
            self::TWIG_INNER_REGEX,
            self::TWIG_METHOD_CALL_REGEX
        );
    }
}
