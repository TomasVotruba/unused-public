<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

final class LocalyUsedPublicProperty
{
    public $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
