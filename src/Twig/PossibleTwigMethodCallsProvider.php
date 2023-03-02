<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Twig;

use Nette\Utils\Strings;
use TomasVotruba\UnusedPublic\Configuration;

final class PossibleTwigMethodCallsProvider
{
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
     * @var string[]
     */
    private array $resolvedTwigMethodNames = [];

    public function __construct(
        private readonly Configuration $configuration
    ) {
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        if ($this->resolvedTwigMethodNames !== []) {
            return $this->resolvedTwigMethodNames;
        }

        $twigMethodNames = [];

        foreach ($this->configuration->getTemplatePaths() as $twigTemplatePath) {
            // @see https://stackoverflow.com/a/36034646/1348344 for glob pattern
            /** @var string[] $twigFilePaths */
            $twigFilePaths = glob($twigTemplatePath . '/{**/*,*}/*.twig', GLOB_BRACE);

            foreach ($twigFilePaths as $twigFilePath) {
                $templateContent = file_get_contents($twigFilePath);
                if ($templateContent === false) {
                    continue;
                }

                $matches = Strings::matchAll($templateContent, self::TWIG_INNER_REGEX);
                foreach ($matches as $match) {
                    $twigContents = $match['contents'];

                    $methodNamesMatches = Strings::matchAll($twigContents, self::TWIG_METHOD_CALL_REGEX);
                    foreach ($methodNamesMatches as $methodNameMatch) {
                        $twigMethodNames[] = $methodNameMatch['method_name'];
                    }
                }
            }
        }

        $this->resolvedTwigMethodNames = $twigMethodNames;

        return $twigMethodNames;
    }
}
