<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum\Template;

final class TwigRegex
{
    /**
     * @see https://regex101.com/r/3gLWCt/1
     */
    public const string INNER_REGEX = '#\{(\{|%)(?<contents>.*?)(\}|%)\}#';

    /**
     * @see https://regex101.com/r/G7zAue/1
     */
    public const string METHOD_CALL_REGEX = '#\w+\.(?<desired_name>\w+)#';
}
