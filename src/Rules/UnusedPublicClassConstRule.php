<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

use Nette\Utils\Arrays;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use TomasVotruba\UnusedPublic\Collectors\ClassConstFetchCollector;
use TomasVotruba\UnusedPublic\Collectors\PublicClassLikeConstCollector;
use TomasVotruba\UnusedPublic\Configuration;
use TomasVotruba\UnusedPublic\Enum\RuleTips;
use TomasVotruba\UnusedPublic\Enum\Template\BladeRegex;
use TomasVotruba\UnusedPublic\Templates\TemplateRegexFinder;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\UnusedPublicClassConstRuleTest
 */
final class UnusedPublicClassConstRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Public constant "%s::%s" is never used';

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Templates\TemplateRegexFinder
     */
    private $templateRegexFinder;

    public function __construct(Configuration $configuration, TemplateRegexFinder $templateRegexFinder)
    {
        $this->configuration = $configuration;
        $this->templateRegexFinder = $templateRegexFinder;
    }

    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * @param CollectedDataNode $node
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isUnusedConstantsEnabled()) {
            return [];
        }

        $bladeConstFetchNames = $this->templateRegexFinder->find(
            $this->configuration->getTemplatePaths(),
            'blade.php',
            [BladeRegex::INNER_REGEX, BladeRegex::TAG_REGEX],
            BladeRegex::CONSTANT_FETCH_REGEX
        );

        $classConstFetchCollector = $node->get(ClassConstFetchCollector::class);
        $publicClassLikeConstCollector = $node->get(PublicClassLikeConstCollector::class);

        $usedConstFetches = Arrays::flatten($classConstFetchCollector);

        $ruleErrors = [];

        foreach ($publicClassLikeConstCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $constantName, $line]) {
                    if ($this->isClassConstantUsed(
                        $className,
                        $constantName,
                        $usedConstFetches,
                        $bladeConstFetchNames
                    )) {
                        continue;
                    }

                    /** @var string $constantName */
                    $errorMessage = sprintf(self::ERROR_MESSAGE, $className, $constantName);

                    $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                        ->file($filePath)
                        ->line($line)
                        ->tip(RuleTips::SOLUTION_MESSAGE)
                        ->build();
                }
            }
        }

        return $ruleErrors;
    }

    /**
     * @param mixed[] $usedConstFetches
     * @param string[] $bladeConstFetchNames
     */
    private function isClassConstantUsed(
        string $className,
        string $constantName,
        array $usedConstFetches,
        array $bladeConstFetchNames
    ): bool {
        // used in template
        if (in_array($constantName, $bladeConstFetchNames, true)) {
            return true;
        }

        $publicConstantReference = $className . '::' . $constantName;

        return in_array($publicConstantReference, $usedConstFetches, true);
    }
}
