<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\CategoryParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\CategoryParser<extended>
 */
class CategoryParserTest extends ParserTestBase
{

    protected string $parserClass = CategoryParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.category',
            'http://pear.php.net/dtd/rest.category.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.category"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'channel' => [
                [
                    'channel' => 'my_c',
                    'name' => 'my_n',
                    'alias' => 'my_a',
                    'description' => 'my_d',
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<c $rootElementAttributes>",
                        '    <n>my_n</n>',
                        '    <c>my_c</c>',
                        '    <a>my_a</a>',
                        '    <d>my_d</d>',
                        '</c>',
                    ],
                ),
            ],
        ];
    }
}
