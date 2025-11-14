<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

/**
 * @var \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1
 */

myFunc([$caller1, 'callIt']);

function myFunc(callable $c)
{
}
