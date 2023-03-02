<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Blade;

use Nette\Utils\Strings;
use TomasVotruba\UnusedPublic\Configuration;

final class PossibleBladeMethodCallsProvider
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
    private const BLADE_METHOD_CALL_REGEX = '#\w+\-\>(?<method_name>\w+)\(\)#';

    /**
     * @var string[]
     */
    private array $resolvedMethodNames = [];

    public function __construct(
        private readonly Configuration $configuration
    ) {
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        if ($this->resolvedMethodNames !== []) {
            return $this->resolvedMethodNames;
        }

        $calledMethodNames = [];

        foreach ($this->configuration->getTemplatePaths() as $templatePath) {
            /** @var string[] $bladeFilePaths */
            $bladeFilePaths = glob($templatePath . '/{**/*,*}/*.blade.php', GLOB_BRACE);

            foreach ($bladeFilePaths as $bladeFilePath) {
                $templateContent = file_get_contents($bladeFilePath);
                if ($templateContent === false) {
                    continue;
                }

                $matches = Strings::matchAll($templateContent, self::BLADE_INNER_REGEX);

                foreach ($matches as $match) {
                    $twigContents = $match['contents'];

                    $methodNamesMatches = Strings::matchAll($twigContents, self::BLADE_METHOD_CALL_REGEX);
                    foreach ($methodNamesMatches as $methodNameMatch) {
                        $calledMethodNames[] = $methodNameMatch['method_name'];
                    }
                }
            }
        }

        $this->resolvedMethodNames = $calledMethodNames;

        return $calledMethodNames;
    }
}
