<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class PackageNameList extends ListBase
{

    public string $channel = '';

    /**
     * @var string[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info['list'] = [
            'type' => 'array',
        ];
        $info += [
            'channel' => ['type' => 'string'],
        ];

        return $info;
    }
}
