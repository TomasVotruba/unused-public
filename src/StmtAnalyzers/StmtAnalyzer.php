<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\StmtAnalyzers;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;

abstract class StmtAnalyzer
{
    /**
     * @return string[]
     */
    abstract protected function getKeys(): array;

    public function isDoc(Stmt $stmt, ClassReflection $classReflection): bool
    {
        if ($classReflection->getResolvedPhpDoc() instanceof ResolvedPhpDocBlock) {
            $resolvedPhpDoc = $classReflection->getResolvedPhpDoc();
            if ($this->isDocComment($resolvedPhpDoc->getPhpDocString())) {
                return true;
            }
        }

        $docComment = $stmt->getDocComment();
        if (! $docComment instanceof Doc) {
            return false;
        }

        return $this->isDocComment($docComment->getText());
    }

    public function isDocComment(string $docComment): bool
    {
        foreach ($this->getKeys() as $key) {
            $key = '@' . ltrim($key, '@');

            if (str_contains($docComment, $key)) {
                return true;
            }
        }

        return false;
    }
}
