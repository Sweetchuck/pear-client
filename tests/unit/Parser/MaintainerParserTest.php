<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\MaintainerParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\MaintainerParser<extended>
 */
class MaintainerParserTest extends ParserTestBase
{
    protected string $parserClass = MaintainerParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.maintainer',
            'http://pear.php.net/dtd/rest.maintainer.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.maintainer"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'all' => [
                [
                    'name' => 'adaniel',
                    'displayName' => 'Adam Daniel',
                    'homepage' => 'http://www.acdaniel.com/',
                    'status' => '1',
                    'role' => 'contributor',
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<a $rootElementAttributes>",
                        '    <h>adaniel</h>',
                        '    <n>Adam Daniel</n>',
                        '    <u>http://www.acdaniel.com/</u>',
                        '    <r>contributor</r>',
                        '    <a>1</a>',
                        '</a>',
                    ],
                ),
            ],
        ];
    }
}
