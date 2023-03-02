<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum\Template;

final class BladeRegex
{
    /**
     * @see https://regex101.com/r/vDKvtE/1
     * @var string
     */
    public const INNER_REGEX = '#\{(\{|\!\!)(?<contents>.*?)(\!\!|\})\}#';

    /**
     * @see https://regex101.com/r/3nbDDK/1
     * @var string
     */
    public const TAG_REGEX = '#@\w+(?<contents>.*?)\n#';

    /**
     * @see https://regex101.com/r/G7zAue/1
     * @var string
     */
    public const METHOD_CALL_REGEX = '#\w+(\-\>|::)(?<desired_name>\w+)\(\)#';

    /**
     * @var string
     * @see https://regex101.com/r/pBkm53/1
     */
    public const CONSTANT_FETCH_REGEX = '#\w+::(?<desired_name>[\w_]+)#';
}
