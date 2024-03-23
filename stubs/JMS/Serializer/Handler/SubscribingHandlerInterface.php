<?php

namespace JMS\Serializer\Handler;

if (interface_exists('JMS\Serializer\Handler\SubscribingHandlerInterface')) {
    return;
}

interface SubscribingHandlerInterface
{
    public static function getSubscribingMethods();
}
