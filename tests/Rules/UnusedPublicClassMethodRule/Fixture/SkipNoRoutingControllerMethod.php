<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class SkipNoRoutingControllerMethod extends Controller
{
    public function someAction()
    {
    }
}
