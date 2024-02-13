<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum;

final class RuleTips
{
    /**
     * @todo this wont make sense anymore, as there will be a rule to make method private/protected
     * @var string
     */
    public const SOLUTION_MESSAGE = 'Consider reducing visibility, annotating it or its class with @api, or with @internal if it is used in tests only';

    /**
     * @var string
     */
    public const NARROW_SCOPE = 'Make it private or protected, or annotate it or its class with @internal if it is used in tests';
}
