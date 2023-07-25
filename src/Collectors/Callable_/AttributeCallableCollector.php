<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Collectors\Callable_;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Type\Constant\ConstantStringType;
use TomasVotruba\UnusedPublic\Configuration;

/**
 * @implements Collector<AttributeGroup, array<string>|null>
 */
final class AttributeCallableCollector implements Collector
{
    /**
     * @readonly
     * @var \TomasVotruba\UnusedPublic\Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getNodeType(): string
    {
        return AttributeGroup::class;
    }

    /**
     * @param AttributeGroup $node
     * @return string[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (! $this->configuration->shouldCollectMethods()) {
            return null;
        }

        foreach ($node->attrs as $attr) {
            $attributeClasName = $attr->name->toString();
            if ($attributeClasName !== 'Symfony\Component\Validator\Constraints\Callback') {
                continue;
            }

            $firstArg = $attr->args[0];
            if (! $firstArg->value instanceof Array_) {
                continue;
            }

            $array = $firstArg->value;
            if (count($array->items) !== 2) {
                continue;
            }

            if (! $array->items[0] instanceof ArrayItem) {
                continue;
            }

            if (! $array->items[1] instanceof ArrayItem) {
                continue;
            }

            $classArrayItem = $array->items[0]->value;
            $methodArrayItem = $array->items[1]->value;

            $classType = $scope->getType($classArrayItem);
            if ($classType instanceof ConstantStringType) {
                $className = $classType->getValue();
            } else {
                continue;
            }

            if ($methodArrayItem instanceof String_) {
                $methodName = $methodArrayItem->value;
            } else {
                continue;
            }

            return [$className . '::' . $methodName];
        }

        return null;
    }
}
