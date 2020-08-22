<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\CategoriesParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\CategoriesParser<extended>
 */
class CategoriesParserTest extends ParserTestBase
{
    protected string $parserClass = CategoriesParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.allcategories',
            'http://pear.php.net/dtd/rest.allcategories.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.allcategories"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'channel' => [
                [
                    'channel' => 'pear.php.net',
                    'list' => [],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<a $rootElementAttributes>",
                        '    <ch>pear.php.net</ch>',
                        '</a>',
                    ],
                ),
            ],
            'categories' => [
                [
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
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<a $rootElementAttributes>",
                        '    <c xlink:href="/rest/c/Audio/info.xml">Audio</c>',
                        '    <c xlink:href="/rest/c/Authentication/info.xml">Authentication</c>',
                        '</a>',
                    ],
                ),
            ],
            'all' => [
                [
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
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<a $rootElementAttributes>",
                        '    <ch>pear.php.net</ch>',
                        '    <c xlink:href="/rest/c/Audio/info.xml">Audio</c>',
                        '    <c xlink:href="/rest/c/Authentication/info.xml">Authentication</c>',
                        '</a>',
                    ],
                ),
            ],
            'empty' => [
                [
                    'channel' => 'pear.php.net',
                    'list' => [],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<a $rootElementAttributes>",
                        '    <ch>pear.php.net</ch>',
                        '    <c></c>',
                        '</a>',
                    ],
                ),
            ],
        ];
    }
}
