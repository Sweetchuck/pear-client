<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class ListBase extends Base
{

    /**
     * @var \Sweetchuck\PearClient\DataType\Base[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'list' => [
                'type' => 'dataType[]',
                'class' => Base::class,
                'primaryKey' => 'name',
            ],
        ];

        return $info;
    }
}
