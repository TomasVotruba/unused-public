<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassConstRule\Fixture;

final class SkipInheritedApiPublicProperty extends BasePropertyClass
{
    public $property = 'overridden!';
}


class BasePropertyClass {
    /**
     * @api
     */
    public $property = 'paths';
}
