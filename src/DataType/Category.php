<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class Category extends Base
{

    public string $channel = '';

    public string $name = '';

    public string $alias = '';

    public string $description = '';

    public string $href = '';

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'channel' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'alias' => ['type' => 'string'],
            'description' => ['type' => 'string'],
            'href' => ['type' => 'string'],
        ];

        return $info;
    }
}
