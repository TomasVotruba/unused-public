<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\StmtAnalyzers;

final class InternalStmtAnalyzer extends StmtAnalyzer
{
    protected function getKeys(): array
    {
        return ['@internal'];
    }
}
