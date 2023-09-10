<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Finder;

final class TemplateFilesFinder
{
    /**
     * @param string[]  $directories
     * @return string[]
     */
    public static function findTemplateFilePaths(array $directories, string $suffix): array
    {
        $templateFilePaths = [];

        foreach ($directories as $directory) {
            /** @var string[] $currentTemplateFilePaths */
            $currentTemplateFilePathsL1 = glob($directory . '/*/*.' . $suffix);
            $currentTemplateFilePathsL2 = glob($directory . '/**/*/*.' . $suffix);

            $currentTemplateFilePaths = array_merge(
                $currentTemplateFilePathsL1 === false ? [] : $currentTemplateFilePathsL1,
                $currentTemplateFilePathsL2 === false ? [] : $currentTemplateFilePathsL2
            );

            $templateFilePaths = array_merge($templateFilePaths, $currentTemplateFilePaths);
        }

        return $templateFilePaths;
    }
}
