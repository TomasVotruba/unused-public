<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\FirstClassCallable;

final class DeserializeArrayToObjectCaller
{
    private function go()
    {
        return SkipDeserializeArrayToObject::deserialize(...);
    }
}
