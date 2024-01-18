<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;

final class InternalDocStmtAnalyzer
{
    public function isInternalDoc(Stmt $stmt, ClassReflection $classReflection): bool
    {
        if ($classReflection->getResolvedPhpDoc() instanceof ResolvedPhpDocBlock) {
            $resolvedPhpDoc = $classReflection->getResolvedPhpDoc();
            if (str_contains($resolvedPhpDoc->getPhpDocString(), '@internal')) {
                return true;
            }
        }

        $docComment = $stmt->getDocComment();
        if (! $docComment instanceof Doc) {
            return false;
        }

        return $this->isInternalDocComment($docComment->getText());
    }

    public function isInternalDocComment(string $docComment): bool
    {
        return str_contains($docComment, '@internal');
    }
}
