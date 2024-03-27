<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\StmtAnalyzers;

final class ApiDocStmtAnalyzer extends StmtAnalyzer
{
    protected function getKeys(): array
    {
        return ['@api'];
    }
}
