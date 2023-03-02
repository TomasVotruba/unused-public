<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Templates;

use Nette\Utils\Strings;

final class TemplateMethodCallsFinder
{
    /**
     * @var array<string, string[]>
     */
    private array $methodCallsByFileSuffix = [];

    /**
     * @param string[] $directories
     * @param string[] $innerRegexes
     * @return string[]
     */
    public function find(array $directories, string $fileSuffix, array $innerRegexes, string $methodCallRegex): array
    {
        if (isset($this->methodCallsByFileSuffix[$fileSuffix])) {
            return $this->methodCallsByFileSuffix[$fileSuffix];
        }

        $templateFilePaths = $this->findTemplateFilePaths($directories, $fileSuffix);

        // convert to file contents
        $templateFilesContents = array_map(
            static fn (string $templateFilePath): string => (string) file_get_contents($templateFilePath),
            $templateFilePaths
        );

        $methodCallNames = [];
        foreach ($templateFilesContents as $templateFileContent) {
            foreach ($innerRegexes as $innerRegex) {
                $matches = Strings::matchAll($templateFileContent, $innerRegex);

                foreach ($matches as $match) {
                    $templateMarkupContents = $match['contents'];

                    $methodNamesMatches = Strings::matchAll($templateMarkupContents, $methodCallRegex);
                    foreach ($methodNamesMatches as $methodNameMatch) {
                        $methodCallNames[] = $methodNameMatch['method_name'];
                    }
                }
            }
        }

        $methodCallNames = array_unique($methodCallNames);

        $this->methodCallsByFileSuffix[$fileSuffix] = $methodCallNames;

        return $methodCallNames;
    }

    /**
     * @param string[]  $directories
     * @return string[]
     */
    private function findTemplateFilePaths(array $directories, string $suffix): array
    {
        $templateFilePaths = [];

        foreach ($directories as $directory) {
            /** @var string[] $currentTemplateFilePaths */
            $currentTemplateFilePaths = glob($directory . '/{**/*,*}/*.' . $suffix, GLOB_BRACE);

            $templateFilePaths = array_merge($templateFilePaths, $currentTemplateFilePaths);
        }

        return $templateFilePaths;
    }
}
