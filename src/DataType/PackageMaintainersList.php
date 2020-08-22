<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class PackageMaintainersList extends ListBase
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
            'packageName' => ['type' => 'string'],
            'channel' => ['type' => 'string'],
        ];

        return $info;
    }
}
