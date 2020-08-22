<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\PackageList;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Package<extended>
 * @covers \Sweetchuck\PearClient\DataType\PackageList<extended>
 *
 * @group categories
 */
class CategoryPackagesTest extends TestBase
{

    protected string $method = 'categoryPackagesGet';

    public function casesGetSuccess(): array
    {
        $uriPattern = 'https://127.0.0.1/rest/c/{categoryName}/packages.xml';
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.categorypackages',
            'http://pear.php.net/dtd/rest.categorypackages.xsd',
        ]);

        return [
            'success.basic' => [
                [
                    'requests' => [
                        [
                            'uri' => strtr($uriPattern, ['{categoryName}' => 'XML']),
                        ],
                    ],
                    'return' => PackageList::__set_state([
                        'list' => [
                            [
                                'name' => 'XML_fo2pdf',
                                'href' => '/rest/p/xml_fo2pdf',
                            ],
                            [
                                'name' => 'Second',
                                'href' => '/rest/p/second',
                            ],
                        ],
                    ]),
                ],
                [
                    [
                        'body' => implode("\n", [
                            '<?xml version="1.0" encoding="UTF-8" ?>',
                            '<l xmlns="http://pear.php.net/dtd/rest.categorypackages"',
                            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                            '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                            "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                            '    <p xlink:href="/rest/p/xml_fo2pdf">XML_fo2pdf</p>',
                            '    <p xlink:href="/rest/p/second">Second</p>',
                            '</l>',
                        ]),
                    ],
                ],
                [],
                [
                    'XML',
                ],
            ],
        ];
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
