<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody as InvalidBodyException;
use Sweetchuck\PearClient\DataType\Category;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Category<extended>
 *
 * @group categories
 */
class CategoryTest extends TestBase
{

    protected string $method = 'categoryGet';

    public function casesGetSuccess(): array
    {
        $uriPattern = 'https://127.0.0.1/rest/c/{{ categoryName }}/info.xml';
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.category',
            'http://pear.php.net/dtd/rest.category.xsd',
        ]);

        return [
            'success.basic' => [
                [
                    'requests' => [
                        [
                            'uri' => strtr($uriPattern, ['{{ categoryName }}' => 'XML']),
                        ],
                    ],
                    'return' => Category::__set_state([
                        'channel' => 'pear.php.net',
                        'name' => 'XML',
                        'alias' => 'XML_alias',
                        'description' => 'none',
                        'href' => '',
                    ]),
                ],
                [
                    [
                        'body' => implode("\n", [
                            '<?xml version="1.0" encoding="UTF-8" ?>',
                            '<c xmlns="http://pear.php.net/dtd/rest.category"',
                            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                            '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                            "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                            '    <n>XML</n>',
                            '    <c>pear.php.net</c>',
                            '    <a>XML_alias</a>',
                            '    <d>none</d>',
                            '</c>',
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
        $endpoint = '/c/{categoryName}/info.xml';
        $uriPattern = "https://127.0.0.1/rest$endpoint";
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.category',
            'http://pear.php.net/dtd/rest.category.xsd',
        ]);

        return [
            'fail.missing.required.name' => [
                [
                    'throwable' => new InvalidBodyException(
                        sprintf(
                            'Body does not match schema for content-type "%s" for %s',
                            'application/json',
                            "Response [get $endpoint 200]",
                        ),
                    ),
                    'requests' => [
                        [
                            'uri' => strtr($uriPattern, ['{categoryName}' => 'XML']),
                        ],
                    ],
                ],
                [
                    [
                        'body' => implode("\n", [
                            '<?xml version="1.0" encoding="UTF-8" ?>',
                            '<c xmlns="http://pear.php.net/dtd/rest.category"',
                            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                            '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                            "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                            '    <c>pear.php.net</c>',
                            '    <a>XML_alias</a>',
                            '    <d>none</d>',
                            '</c>',
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
}
