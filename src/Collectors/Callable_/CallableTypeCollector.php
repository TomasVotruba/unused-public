<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\Callable_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Constant\ConstantArrayType;
use TomasVotruba\UnusedPublic\ClassTypeDetector;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<Expr\Array_, non-empty-array<string>|null>
 */
final readonly class CallableTypeCollector implements Collector
{
    public function __construct(
        private Configuration $configuration,
        private ClassTypeDetector $classTypeDetector,
    ) {
    }

    public function getNodeType(): string
    {
        return Expr\Array_::class;
    }

    /**
     * @param Expr\Array_ $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        // skip calls in tests, as they are not used in production
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $this->classTypeDetector->isTestClass($classReflection)) {
            return null;
        }

        $callableType = $scope->getType($node);
        if (! $callableType instanceof ConstantArrayType) {
            return null;
        }

        $typeAndMethodNames = $callableType->findTypeAndMethodNames();
        if ($typeAndMethodNames === []) {
            return null;
        }

        $classMethodReferences = [];
        foreach ($typeAndMethodNames as $typeAndMethodName) {
            if ($typeAndMethodName->isUnknown()) {
                continue;
            }

            $objectClassNames = $typeAndMethodName->getType()
                ->getObjectClassNames();
            foreach ($objectClassNames as $objectClassName) {
                $classMethodReferences[] = $objectClassName . '::' . $typeAndMethodName->getMethod();
            }
        }

        return $classMethodReferences;
    }
}
