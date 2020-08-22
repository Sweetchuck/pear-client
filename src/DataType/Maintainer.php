<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class Maintainer extends Base
{

    public string $href = '';

    public string $name = '';

    public string $displayName = '';

    public string $homepage = '';

    public ?int $status = null;

    public string $role = '';

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'href' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'displayName' => ['type' => 'string'],
            'homepage' => ['type' => 'string'],
            'status' => ['type' => 'integer'],
            'role' => ['type' => 'string'],
        ];

        return $info;
    }
}
