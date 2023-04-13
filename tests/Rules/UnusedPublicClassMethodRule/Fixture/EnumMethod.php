<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\SomeEnum;

final class EnumMethod
{
    public function testUseEnum(SomeEnum $e)
    {
        $e->useMe();
        SomeEnum::useMeStatic();
    }

}


