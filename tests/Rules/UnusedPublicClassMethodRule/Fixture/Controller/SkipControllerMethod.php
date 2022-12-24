<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Controller;

use Symfony\Component\Routing\Annotation\Route;

final class SkipControllerMethod
{
    /**
     * @Route()
     */
    public function someAction()
    {
    }

    #[Route]
    public function anotherAction()
    {
    }
}
