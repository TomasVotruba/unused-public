<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

class PropertyUsedViaSubClass
{
    private SubClass $prop;

    protected function doFoo()
    {
        $this->prop->x = 1;
    }
}

class SubClass extends BaseClass
{
}

class BaseClass
{
    public int $x;
}
