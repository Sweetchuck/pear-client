<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\PackageMaintainersParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\PackageMaintainersParser<extended>
 */
class PackageMaintainersParserTest extends ParserTestBase
{
    protected string $parserClass = PackageMaintainersParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.packagemaintainers',
            'http://pear.php.net/dtd/rest.packagemaintainers.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.packagemaintainers"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'all' => [
                [
                    'channel' => 'pear.php.net',
                    'packageName' => 'Archive_Zip',
                    'list' => [
                        'vblavet' => [
                            'name' => 'vblavet',
                            'status' => 1,
                        ],
                        'richard' => [
                            'name' => 'richard',
                            'status' => 0,
                        ],
                        'alec' => [
                            'name' => 'alec',
                            'status' => 1,
                        ],
                    ],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<m $rootElementAttributes>",
                        '  <p>Archive_Zip</p>',
                        '  <c>pear.php.net</c>',
                        '  <m>',
                        '    <h>vblavet</h>',
                        '    <a>1</a>',
                        '  </m>',
                        '  <m>',
                        '    <h>richard</h>',
                        '    <a>0</a>',
                        '  </m>',
                        '  <m>',
                        '    <h>alec</h>',
                        '    <a>1</a>',
                        '  </m>',
                        '</m>',
                    ],
                ),
            ],
        ];
    }
}
