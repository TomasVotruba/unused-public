<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

class BasePrivatePropertyClass {
    /**
     * @api
     */
    private $property = 'private';
}
