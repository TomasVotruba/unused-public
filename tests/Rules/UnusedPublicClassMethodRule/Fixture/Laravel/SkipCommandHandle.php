<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Laravel;

use Illuminate\Console\Command;

final class SkipCommandHandle extends Command
{
    public function handle()
    {
    }
}
