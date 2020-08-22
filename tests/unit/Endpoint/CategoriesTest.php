<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\CategoryList;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\CategoryList<extended>
 * @covers \Sweetchuck\PearClient\DataType\Category<extended>
 *
 * @group categories
 */
class CategoriesTest extends TestBase
{

    protected string $method = 'categoriesGet';

    public function casesGetSuccess(): array
    {
        $uri = 'https://127.0.0.1/rest/c/categories.xml';
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.allcategories',
            'http://pear.php.net/dtd/rest.allcategories.xsd',
        ]);

        $case = [
            'dummy' => [
                'expected' => [
                    'return' => CategoryList::__set_state([
                        'channel' => 'pear.php.net',
                        'list' => [
                            [
                                'href' => '/rest/c/Audio/info.xml',
                                'name' => 'Audio',
                            ],
                            [
                                'href' => '/rest/c/Authentication/info.xml',
                                'name' => 'Authentication',
                            ],
                        ],
                    ]),
                ],
                'responses' => [
                    [
                        'body' => implode("\n", [
                            '<?xml version="1.0" encoding="UTF-8" ?>',
                            '<a xmlns="http://pear.php.net/dtd/rest.allcategories"',
                            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                            '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                            "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                            '    <ch>pear.php.net</ch>',
                            '    <c xlink:href="/rest/c/Audio/info.xml">Audio</c>',
                            '    <c xlink:href="/rest/c/Authentication/info.xml">Authentication</c>',
                            '</a>',
                        ]),
                    ],
                ],
                'clientOptions' => [],
                'methodArgs' => [],

            ],
        ];

        return [
            'basic' => [
                [
                    'requests' => [
                        [
                            'uri' => $uri,
                        ],
                    ],
                    'return' => $case['dummy']['expected']['return'],
                ],
                $case['dummy']['responses'],
            ],
        ];
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
