<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\SkipDeserializeArrayToObject;

final class DeserializeArrayToObjectCaller
{
    private function go()
    {
        array_map(SkipDeserializeArrayToObject::deserialize(...), []);
    }

    private function goAgain()
    {
        array_map([SkipDeserializeArrayToObject::class, 'deserialize'], []);
    }
}
