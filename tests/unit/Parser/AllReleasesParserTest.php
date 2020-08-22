<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Codeception\Test\Unit;
use DOMDocument;
use Sweetchuck\PearClient\Parser\AllReleasesParser;
use Sweetchuck\PearClient\Test\UnitTester;

/**
 * @covers \Sweetchuck\PearClient\Parser\AllReleasesParser<extended>
 */
class AllReleasesParserTest extends ParserTestBase
{

    protected string $parserClass = AllReleasesParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.allreleases',
            'http://pear.php.net/dtd/rest.allreleases.xsd',
        ]);

        $rootAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.allreleases"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'all' => [
                [
                    'channel' => 'pear.php.net',
                    'packageName' => 'myPackageName01',
                    'list' => [
                        '1.2.3' => [
                            'version' => '1.2.3',
                            'stability' => 'beta',
                        ],
                        '4.5.6' => [
                            'version' => '4.5.6',
                            'stability' => 'stable',
                        ],
                    ],
                ],
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8" ?>',
                    "<a {$rootAttributes}>",
                    '',
                    '    <p>myPackageName01</p>',
                    '    <c>pear.php.net</c>',
                    '    <r>',
                    '        <v>1.2.3</v>',
                    '        <s>beta</s>',
                    '    </r>',
                    '    <r>',
                    '        <v>4.5.6</v>',
                    '        <s>stable</s>',
                    '    </r>',
                    '</a>',
                ]),
            ],
        ];
    }
}
