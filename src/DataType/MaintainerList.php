<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class MaintainerList extends ListBase
{

    public string $channel = '';

    public string $packageName = '';

    /**
     * @var \Sweetchuck\PearClient\DataType\Maintainer[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info['list']['class'] = Maintainer::class;
        $info += [
            'channel' => ['type' => 'string'],
            'packageName' => ['type' => 'string'],
        ];

        return $info;
    }
}
