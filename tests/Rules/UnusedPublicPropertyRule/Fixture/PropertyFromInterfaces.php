<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source\InterfaceWithProperty;

class PropertyFromInterfaces implements InterfaceWithProperty
{
    public string $prop = 'value';
}

function func(): ?InterfaceWithProperty
{
    return rand(0, 1) == 1 ? new PropertyFromInterfaces() : null;
}

$i = func();
echo $i?->prop;
