<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\AllMaintainersParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\AllMaintainersParser<extended>
 */
class AllMaintainersParserTest extends ParserTestBase
{

    protected string $parserClass = AllMaintainersParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.allmaintainers',
            'http://pear.php.net/dtd/rest.allmaintainers.xsd',
        ]);

        $rootAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.allmaintainers"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'channel' => [
                [
                    'list' => [
                        'ab' => [
                            'href' => '/rest/m/ab',
                            'name' => 'ab',
                        ],
                        'abhradke' => [
                            'href' => '/rest/m/abhradke',
                            'name' => 'abhradke',
                        ],
                    ],
                ],
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8" ?>',
                    "<m {$rootAttributes}>",
                    '    <h xlink:href="/rest/m/ab">ab</h>',
                    '    <h xlink:href="/rest/m/abhradke">abhradke</h>',
                    '</m>',
                ]),
            ],
        ];
    }
}
