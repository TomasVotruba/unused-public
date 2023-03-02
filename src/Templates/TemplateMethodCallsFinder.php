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
     * @return string[]
     */
    public function find(array $directories, string $fileSuffix, string $innerRegex, string $methodCallRegex): array
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
            $matches = Strings::matchAll($templateFileContent, $innerRegex);

            foreach ($matches as $match) {
                $twigContents = $match['contents'];

                $methodNamesMatches = Strings::matchAll($twigContents, $methodCallRegex);
                foreach ($methodNamesMatches as $methodNameMatch) {
                    $methodCallNames[] = $methodNameMatch['method_name'];
                }
            }
        }

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
