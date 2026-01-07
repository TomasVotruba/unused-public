<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Enum;

final class RuleTips
{
    /**
     * @todo this wont make sense anymore, as there will be a rule to make method private/protected
     */
    public const string SOLUTION_MESSAGE = 'Either reduce visibility or annotate it or its class with @api';

    public const string NARROW_SCOPE = 'Make it private or protected';
}
