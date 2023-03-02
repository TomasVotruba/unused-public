<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum\Template;

class TwigRegex
{
    /**
     * @see https://regex101.com/r/3gLWCt/1
     * @var string
     */
    public const TWIG_INNER_REGEX = '#\{(\{|%)(?<contents>.*?)(\}|%)\}#';

    /**
     * @see https://regex101.com/r/G7zAue/1
     * @var string
     */
    public const TWIG_METHOD_CALL_REGEX = '#\w+\.(?<desired_name>\w+)#';
}
