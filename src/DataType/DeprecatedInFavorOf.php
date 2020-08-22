<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class DeprecatedInFavorOf extends Base
{

    public ?string $channel = null;

    public ?string $name = null;

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'channel' => ['type' => 'string'],
            'name' => ['type' => 'string'],
        ];

        return $info;
    }
}
