<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum;

final class RuleTips
{
    /**
     * @todo this wont make sense anymore, as there will be a rule to make method private/protected
     * @var string
     */
    public const SOLUTION_MESSAGE = 'Either reduce visibility or annotate it or its class with @api, @internal, or @required';

    /**
     * @var string
     */
    public const NARROW_SCOPE = 'Make it private or protected';
}
