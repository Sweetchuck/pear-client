<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class ReleaseList extends ListBase
{

    public string $channel = '';

    public string $packageName = '';

    /**
     * @var \Sweetchuck\PearClient\DataType\Release[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info['list']['class'] = Release::class;
        $info['list']['primaryKey'] = 'version';
        $info += [
            'channel' => ['type' => 'string'],
            'packageName' => ['type' => 'string'],
        ];

        return $info;
    }
}
