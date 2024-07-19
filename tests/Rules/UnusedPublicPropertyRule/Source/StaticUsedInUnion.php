<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Source;

use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\StaticUsedInUnionA;
use TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture\StaticUsedInUnionB;

function doFooBar(StaticUsedInUnionA|StaticUsedInUnionB $aOrB): void {
    echo $aOrB::$amount;
}

