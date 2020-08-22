<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\CategoryPackagesParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\CategoryPackagesParser<extended>
 */
class CategoryPackagesParserTest extends ParserTestBase
{

    protected string $parserClass = CategoryPackagesParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.categorypackages',
            'http://pear.php.net/dtd/rest.categorypackages.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.categorypackages"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'all' => [
                [
                    'list' => [
                        'Net_NNTP' => [
                            'href' => '/rest/p/net_nntp',
                            'name' => 'Net_NNTP'
                        ],
                        'Mail_IMAPv2' => [
                            'href' => '/rest/p/mail_imapv2',
                            'name' => 'Mail_IMAPv2',
                        ],
                    ],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<l $rootElementAttributes>",
                        '  <p xlink:href="/rest/p/net_nntp">Net_NNTP</p>',
                        '  <p xlink:href="/rest/p/mail_imapv2">Mail_IMAPv2</p>',
                        '</l>',
                    ],
                ),
            ],
        ];
    }
}
