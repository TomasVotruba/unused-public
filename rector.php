<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withSkip(['*/Fixture/*', '*/Source/*'])
    ->withImportNames()
    ->withPhpSets()
    ->withPreparedSets(
        typeDeclarations: true,
        codingStyle: true,
        deadCode: true,
        privatization: true,
        naming: true,
        codeQuality: true
    )
    ->withConfiguredRule(StringClassNameToClassConstantRector::class, [
        'Twig\Extension\ExtensionInterface',
        'PHPUnit\Framework\TestCase',
        'Symfony\Bundle\FrameworkBundle\Controller\Controller',
        'Symfony\Component\EventDispatcher\EventSubscriberInterface',
    ]);
