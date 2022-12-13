<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Fixture;

use TomasVotruba\UnusedPublicPHPStanRules\Tests\Rules\DeadCode\UnusedPublicClassMethodRule\Source\ParentClassWithPublicMethod;

final class SkipParentMethodOverride extends ParentClassWithPublicMethod
{
    public function parentMethod()
    {
    }
}
