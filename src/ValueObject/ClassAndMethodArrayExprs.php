<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\ValueObject;

use PhpParser\Node\Expr;

final readonly class ClassAndMethodArrayExprs
{
    public function __construct(
        private Expr $classExpr,
        private Expr $methodExpr
    ) {
    }

    public function getClassExpr(): Expr
    {
        return $this->classExpr;
    }

    public function getMethodExpr(): Expr
    {
        return $this->methodExpr;
    }
}
