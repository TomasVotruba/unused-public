<?php

declare(strict_types=1);

namespace Doctrine\Common;

interface EventSubscriber
{
    /**
     * @return string[]
     */
    public function getSubscribedEvents();
}
