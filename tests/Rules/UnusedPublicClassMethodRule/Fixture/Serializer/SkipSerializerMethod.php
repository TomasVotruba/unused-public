<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;

final class SkipSerializerMethod implements SubscribingHandlerInterface
{
    public function localMethod(): int
    {
        return 100;
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
