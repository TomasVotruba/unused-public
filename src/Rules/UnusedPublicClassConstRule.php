<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Rules;

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
use TomasVotruba\UnusedPublic\Utils\Arrays;
use TomasVotruba\UnusedPublic\ValueObject\ConstantReference;

/**
 * @see \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\UnusedPublicClassConstRuleTest
 */
final class UnusedPublicClassConstRule implements Rule
{
    /**
     * @var string
     *
     * @api
     */
    public const ERROR_MESSAGE = 'Public constant "%s::%s" is never used';

    public function __construct(
        private readonly Configuration $configuration,
        private readonly TemplateRegexFinder $templateRegexFinder,
    ) {
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

        $usedConstFetches = array_map(static function (string $constantReference): ConstantReference {
            return ConstantReference::fromString($constantReference);
        }, Arrays::flatten($classConstFetchCollector));

        $ruleErrors = [];

        foreach ($publicClassLikeConstCollector as $filePath => $declarationsGroups) {
            foreach ($declarationsGroups as $declarationGroup) {
                foreach ($declarationGroup as [$className, $constantName, $line, $isInternal]) {
                    if ($this->isClassConstantUsed(
                        $className,
                        $constantName,
                        $usedConstFetches,
                        $bladeConstFetchNames,
                        $isInternal,
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
     * @param ConstantReference[] $usedConstFetches
     * @param string[] $bladeConstFetchNames
     */
    private function isClassConstantUsed(
        string $className,
        string $constantName,
        array $usedConstFetches,
        array $bladeConstFetchNames,
        bool $isInternal,
    ): bool {
        // used in template
        if (in_array($constantName, $bladeConstFetchNames, true)) {
            return true;
        }

        $publicConstantReference = $className . '::' . $constantName;

        foreach ($usedConstFetches as $usedConstFetch) {
            // skip calls in tests, if they are not internal
            if (! $isInternal && $usedConstFetch->isTest()) {
                continue;
            }

            $usedConstReference = $usedConstFetch->getClass() . '::' . $usedConstFetch->getConstant();
            if ($usedConstReference === $publicConstantReference) {
                return true;
            }
        }

        return false;
    }
}
