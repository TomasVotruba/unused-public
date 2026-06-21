<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum\Template;

final class BladeRegex
{
    /**
     * @see https://regex101.com/r/vDKvtE/1
     */
    public const string INNER_REGEX = '#\{(\{|\!\!)(?<contents>.*?)(\!\!|\})\}#';

    /**
     * @see https://regex101.com/r/3nbDDK/1
     */
    public const string TAG_REGEX = '#@\w+(?<contents>.*?)\n#';

    /**
     * @see https://regex101.com/r/P1EaIR/1
     */
    public const string METHOD_CALL_REGEX = '#\w+(\-\>|::)(?<desired_name>\w+)\((.*?)\)#';

    /**
     * Matches a property fetch "$var->name" that is not a method call, e.g. {{ $value->name }}
     */
    public const string PROPERTY_FETCH_REGEX = '#\-\>(?<desired_name>\w+)(?!\s*\()#';

    /**
     * @see https://regex101.com/r/pBkm53/1
     */
    public const string CONSTANT_FETCH_REGEX = '#\w+::(?<desired_name>[\w_]+)#';
}
