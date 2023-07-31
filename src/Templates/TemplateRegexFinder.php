<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Templates;

use TomasVotruba\UnusedPublic\Finder\TemplateFilesFinder;

final class TemplateRegexFinder
{
    /**
     * @var array<string, string[]>
     */
    private array $resultsByCacheKey = [];

    /**
     * @param string[] $directories
     * @param string[] $innerRegexes
     * @return string[]
     */
    public function find(array $directories, string $fileSuffix, array $innerRegexes, string $targetRegex): array
    {
        $cacheKey = md5($fileSuffix . $targetRegex);

        if (isset($this->resultsByCacheKey[$cacheKey])) {
            return $this->resultsByCacheKey[$cacheKey];
        }

        $templateFilePaths = TemplateFilesFinder::findTemplateFilePaths($directories, $fileSuffix);

        // convert to file contents
        $templateFilesContents = array_map(
            static fn (string $templateFilePath): string => (string) file_get_contents($templateFilePath),
            $templateFilePaths
        );

        $methodCallNames = [];
        foreach ($templateFilesContents as $templateFileContent) {
            foreach ($innerRegexes as $innerRegex) {
                preg_match_all($innerRegex, $templateFileContent, $matches);

                foreach ($matches as $match) {
                    $templateMarkupContents = $match['contents'];

                    preg_match_all($targetRegex, $templateMarkupContents, $methodNamesMatches);

                    foreach ($methodNamesMatches as $methodNameMatch) {
                        $methodCallNames[] = $methodNameMatch['desired_name'];
                    }
                }
            }
        }

        $methodCallNames = array_unique($methodCallNames);

        $this->resultsByCacheKey[$fileSuffix] = $methodCallNames;

        return $methodCallNames;
    }
}
