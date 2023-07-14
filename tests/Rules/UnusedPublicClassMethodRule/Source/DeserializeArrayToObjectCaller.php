<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\SkipDeserializeArrayToObject;

final class DeserializeArrayToObjectCaller
{
    private function go()
    {
        return SkipDeserializeArrayToObject::deserialize(...);
    }
}
