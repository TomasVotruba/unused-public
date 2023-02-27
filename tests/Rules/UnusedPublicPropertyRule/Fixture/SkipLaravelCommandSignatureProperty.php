<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

use Illuminate\Console\Command;

final class SkipLaravelCommandSignatureProperty extends Command
{
    protected $signature;

    protected $desciption;
}
