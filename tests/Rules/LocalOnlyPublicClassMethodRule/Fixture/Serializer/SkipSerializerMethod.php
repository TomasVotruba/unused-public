<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\LocalOnlyPublicClassMethodRule\Fixture\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;

final class SkipSerializerMethod implements SubscribingHandlerInterface
{
    public function localMethod()
    {
    }

    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => 'left',
                'format' => 'json',
                'method' => 'localMethod',
            ],
        ];
    }
}
