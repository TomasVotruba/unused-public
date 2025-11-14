<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Doctrine;

use Doctrine\Common\EventSubscriber;

final class SkipDoctrineEventSubscriber implements EventSubscriber
{
    public function preFlush()
    {
    }

    public function getSubscribedEvents()
    {
        return ['preFlush'];
    }
}
