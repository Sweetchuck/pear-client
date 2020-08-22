<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class Dependency extends Base
{
    public ?string $version = null;

    public array $definitions = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'version' => ['type' => 'string'],
            'definitions' => ['type' => 'array'],
        ];

        return $info;
    }
}
