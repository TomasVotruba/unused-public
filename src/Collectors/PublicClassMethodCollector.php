<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\MethodTypeDetector;
use TomasVotruba\UnusedPublic\PublicClassMethodMatcher;
use TomasVotruba\UnusedPublic\StmtAnalyzers\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\InternalStmtAnalyzer;
use TomasVotruba\UnusedPublic\StmtAnalyzers\RequiredStmtAnalyzer;

/**
 * @implements Collector<ClassMethod, array{class-string, string, int}|null>
 */
final class PublicClassMethodCollector implements Collector
{
    /**
     * @var string[]
     */
    private const SKIPPED_TYPES = [
        // symfony
        'Symfony\Component\EventDispatcher\EventSubscriberInterface',
        'JMS\Serializer\Handler\SubscribingHandlerInterface',
        'Twig\Extension\ExtensionInterface',
        'Symfony\Bundle\FrameworkBundle\Controller\Controller',
        // laravel
        'Illuminate\Console\Command',
        'Livewire\Component',
        'Illuminate\Http\Request',
        'Illuminate\Contracts\Mail\Mailable',
        'Illuminate\Contracts\Queue\ShouldQueue',
        'Illuminate\Support\ServiceProvider',
    ];

    public function __construct(
        private readonly ApiDocStmtAnalyzer $apiDocStmtAnalyzer,
        private readonly InternalStmtAnalyzer $internalStmtAnalyzer,
        private readonly RequiredStmtAnalyzer $requiredStmtAnalyzer,
        private readonly PublicClassMethodMatcher $publicClassMethodMatcher,
        private readonly MethodTypeDetector $methodTypeDetector,
        private readonly Configuration $configuration,
    ) {
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return array{class-string, string, int}|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->shouldSkip($classReflection, $node, $scope)) {
            return null;
        }

        if ($this->publicClassMethodMatcher->shouldSkipClassMethod($node)) {
            return null;
        }

        if (
            $this->apiDocStmtAnalyzer->isDoc($node, $classReflection) ||
            $this->internalStmtAnalyzer->isDoc($node, $classReflection) ||
            $this->requiredStmtAnalyzer->isDoc($node, $classReflection)
        ) {
            return null;
        }

        if ($this->isSkippedType($classReflection)) {
            return null;
        }

        if ($this->publicClassMethodMatcher->shouldSkipClassReflection($classReflection)) {
            return null;
        }

        $methodName = $node->name->toString();

        // is this method required by parent contract? skip it
        if ($this->publicClassMethodMatcher->isUsedByParentClassOrInterface($classReflection, $methodName)) {
            return null;
        }

        return [$classReflection->getName(), $methodName, $node->getLine()];
    }

    private function shouldSkip(ClassReflection $classReflection, ClassMethod $classMethod, Scope $scope): bool
    {
        // skip acceptance tests, codeception
        if (str_ends_with($classReflection->getName(), 'Cest')) {
            return true;
        }

        if ($this->methodTypeDetector->isTestMethod($classMethod, $scope)) {
            return true;
        }

        return $this->methodTypeDetector->isTraitMethod($classMethod, $scope);
    }

    private function isSkippedType(ClassReflection $classReflection): bool
    {
        foreach (self::SKIPPED_TYPES as $skippedType) {
            if ($classReflection->isSubclassOf($skippedType)) {
                return true;
            }
        }

        return false;
    }
}
