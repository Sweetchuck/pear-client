<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class Release extends Base
{
    public ?string $version = null;

    public ?string $stability = null;

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'version' => ['type' => 'string'],
            'stability' => ['type' => 'string'],
        ];

        return $info;
    }
}
