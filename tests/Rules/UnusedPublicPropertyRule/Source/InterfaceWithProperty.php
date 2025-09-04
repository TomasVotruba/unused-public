<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

interface InterfaceWithProperty
{
    public string $prop {get; }
}
