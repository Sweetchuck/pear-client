<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

class Package extends Base
{

    public ?string $name = null;

    public ?string $href = null;

    public ?string $channel = null;

    public ?Category $category = null;

    public ?string $license = null;

    public ?string $summary = null;

    public ?string $description = null;

    public ?DeprecatedInFavorOf $deprecatedInFavorOf = null;

    public ?string $releasesHref = null;

    /**
     * @var \Sweetchuck\PearClient\DataType\Release[]
     */
    public array $releases = [];

    /**
     * @var \Sweetchuck\PearClient\DataType\Dependency[]
     */
    public array $dependencies = [];

    public static function getPropertyInfo(): array
    {
        $info = parent::getPropertyInfo();
        $info += [
            'name' => ['type' => 'string'],
            'href' => ['type' => 'string'],
            'channel' => ['type' => 'string'],
            'category' => [
                'type' => 'dataType',
                'class' => Category::class,
            ],
            'license' => ['type' => 'string'],
            'summary' => ['type' => 'string'],
            'description' => ['type' => 'string'],
            'deprecatedInFavorOf' => [
                'type' => 'dataType',
                'class' => DeprecatedInFavorOf::class,
            ],
            'releasesHref' => ['type' => 'string'],
            'releases' => [
                'type' => 'dataType[]',
                'class' => Release::class,
                'primaryKey' => 'version',
            ],
            'dependencies' => [
                'type' => 'dataType[]',
                'class' => Dependency::class,
                'primaryKey' => 'version',
            ],
        ];

        return $info;
    }
}
