<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\ResolvedMethodReflection;
use TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\PublicClassMethodMatcher;

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

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\ApiDocStmtAnalyzer
     */
    private $apiDocStmtAnalyzer;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\PublicClassMethodMatcher
     */
    private $publicClassMethodMatcher;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    public function __construct(
        ApiDocStmtAnalyzer $apiDocStmtAnalyzer,
        PublicClassMethodMatcher $publicClassMethodMatcher,
        Configuration $configuration
    ) {
        $this->apiDocStmtAnalyzer = $apiDocStmtAnalyzer;
        $this->publicClassMethodMatcher = $publicClassMethodMatcher;
        $this->configuration = $configuration;
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return array<array{class-string, string, int}>|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        if ($this->isTestMethod($node, $scope)) {
            return null;
        }

        if ($this->isTraitMethod($node, $scope)) {
            return null;
        }

        $classReflection = $scope->getClassReflection();

        // skip
        if ($classReflection instanceof ClassReflection) {
            // skip acceptance tests, codeception
            if (substr_compare($classReflection->getName(), 'Cest', -strlen('Cest')) === 0) {
                return null;
            }

            foreach (self::SKIPPED_TYPES as $skippedType) {
                if ($classReflection->isSubclassOf($skippedType)) {
                    return null;
                }
            }
        }

        if ($this->publicClassMethodMatcher->shouldSkipClassMethod($node)) {
            return null;
        }

        // only if the class has no parents/implementers, to avoid class method required by contracts
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->apiDocStmtAnalyzer->isApiDoc($node, $classReflection)) {
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

    private function isTestMethod(ClassMethod $classMethod, Scope $scope): bool
    {
        $classMethodName = $classMethod->name->toString();
        if (strncmp($classMethodName, 'test', strlen('test')) === 0) {
            return true;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $extendedMethodReflection = $classReflection->getMethod($classMethodName, $scope);

        if ($extendedMethodReflection->getDocComment() === null) {
            return false;
        }

        return strpos($extendedMethodReflection->getDocComment(), '@test') !== false;
    }

    private function isTraitMethod(ClassMethod $classMethod, Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $extendedMethodReflection = $classReflection->getMethod($classMethod->name->toString(), $scope);
        if ($extendedMethodReflection instanceof PhpMethodReflection || $extendedMethodReflection instanceof ResolvedMethodReflection) {
            return $extendedMethodReflection->getDeclaringTrait() instanceof ClassReflection;
        }

        return false;
    }
}
