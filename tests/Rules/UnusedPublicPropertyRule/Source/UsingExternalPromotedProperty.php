<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\LocallyUsedPromotedProperty;

final class UsingExternalPromotedProperty
{
    public function run(LocallyUsedPromotedProperty $locallyUsedPromotedProperty): string
    {
        return $locallyUsedPromotedProperty->name;
    }
}
