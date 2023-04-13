<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source;

trait SomeTrait
{
    public function useMe()
    {
    }

    public function unusedMethod()
    {
    }
}
