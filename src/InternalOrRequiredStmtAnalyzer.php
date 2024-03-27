<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;

final class InternalOrRequiredStmtAnalyzer
{
    public function isInternalOrRequired(Stmt $stmt, ClassReflection $classReflection): bool
    {
        if ($classReflection->getResolvedPhpDoc() instanceof ResolvedPhpDocBlock) {
            $resolvedPhpDoc = $classReflection->getResolvedPhpDoc();
            if ($this->isInternalOrRequiredComment($resolvedPhpDoc->getPhpDocString())) {
                return true;
            }
        }

        $docComment = $stmt->getDocComment();
        if (! $docComment instanceof Doc) {
            return false;
        }

        return $this->isInternalOrRequiredComment($docComment->getText());
    }

    public function isInternalOrRequiredComment(string $docComment): bool
    {
        return str_contains(strtolower($docComment), '@required') || str_contains(strtolower($docComment), '@internal');
    }
}
