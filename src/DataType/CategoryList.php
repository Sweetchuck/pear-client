<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class CategoryList extends Base
{

    public string $channel = '';

    /**
     * @var \Sweetchuck\PearClient\DataType\Category[]
     */
    public array $list = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'channel' => ['type' => 'string'],
            'list' => [
                'type' => 'dataType[]',
                'class' => Category::class,
                'primaryKey' => null,
            ],
        ];

        return $info;
    }
}
