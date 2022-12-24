<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class SkipNoRoutingControllerMethod extends Controller
{
    public function someAction()
    {
    }
}
