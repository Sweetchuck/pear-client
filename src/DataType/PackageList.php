<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class PackageList extends ListBase
{

    /**
     * @var \Sweetchuck\PearClient\DataType\Package[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info['list']['class'] = Package::class;

        return $info;
    }
}
