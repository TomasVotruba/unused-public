<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Utils;

final class Strings
{
    /**
     * @return mixed[]
     */
    public static function matchAll(string $content, string $regex): array
    {
        preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
        return $matches;
    }

    /**
     * @param string[] $values
     * @return string[]
     */
    public static function lowercase(array $values): array
    {
        return array_map(static fn (string $value): string => strtolower($value), $values);
    }
}
