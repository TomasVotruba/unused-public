<?php

namespace TomasVotruba\UnusedPublic\Tests\Templates\TemplateFilesFinder;

use PHPUnit\Framework\TestCase;
use TomasVotruba\UnusedPublic\Finder\TemplateFilesFinder;

final class TemplateFilesFinderTest extends TestCase
{
    public function test(): void
    {
        $twigFilePaths = TemplateFilesFinder::findTemplateFilePaths([__DIR__ . '/Fixture'], 'twig');
        $this->assertCount(2, $twigFilePaths);
    }
}
