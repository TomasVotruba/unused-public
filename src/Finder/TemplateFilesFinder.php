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
            $currentTemplateFilePaths = glob($directory . '/*/*.' . $suffix);
            $currentTemplateFilePaths = array_merge(glob($directory . '/**/*/*.' . $suffix), $currentTemplateFilePaths);
            $currentTemplateFilePaths = array_unique($currentTemplateFilePaths);

            $templateFilePaths = array_merge($templateFilePaths, $currentTemplateFilePaths);
        }

        return $templateFilePaths;
    }
}
