<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassConstRule\Fixture;

final class SkipApiPublicConstant
{
    /**
     * @api
     */
    public const PATHS = 'paths';
}
