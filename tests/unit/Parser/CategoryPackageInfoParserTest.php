<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\CategoryPackageInfoParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\CategoryPackageInfoParser<extended>
 */
class CategoryPackageInfoParserTest extends ParserTestBase
{

    protected string $parserClass = CategoryPackageInfoParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.categorypackageinfo',
            'http://pear.php.net/dtd/rest.categorypackageinfo.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.categorypackageinfo"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        $dependenciesDefinition = [
            'required' => [
                'php' => [
                    'min' => '4.0.0',
                ],
                'pearinstaller' => [
                    'min' => '1.4.0b1',
                ],
                'extension' => [
                    'name' => 'imap',
                ],
            ],
            'optional' => [
                'package' => [
                    'name' => 'Net_URL',
                    'channel' => 'pear.php.net',
                ],
            ],
        ];

        return [
            'all' => [
                [
                    'list' => [
                        'Mail_IMAPv2' => [
                            'name' => 'Mail_IMAPv2',
                            'channel' => 'pear.php.net',
                            'category' => [
                                'name' => 'Mail',
                            ],
                            'license' => 'my_label',
                            'summary' => 'my_summary',
                            'description' => 'my_description',
                            'href' => '/rest/r/mail_imapv2',
                            'deprecatedInFavorOf' => [
                                'channel' => 'pear.php.net',
                                'name' => 'Mail_IMAPv2',
                            ],
                            'releases' => [
                                '0.2.1' => [
                                'version' => '0.2.1',
                                    'stability' => 'beta',
                                ],
                                '0.2.0' => [
                                    'version' => '0.2.0',
                                    'stability' => 'beta',
                                ],
                            ],
                            'dependencies' => [
                                '0.2.1' => [
                                    'version' => '0.2.1',
                                    'definitions' => $dependenciesDefinition,
                                ],
                            ],
                        ],
                    ],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<f $rootElementAttributes>",
                        '  <pi>',
                        '    <p>',
                        '      <n>Mail_IMAPv2</n>',
                        '      <c>pear.php.net</c>',
                        '      <ca xlink:href="/rest/c/Mail">Mail</ca>',
                        '      <l>my_label</l>',
                        '      <s>my_summary</s>',
                        '      <d>my_description</d>',
                        '      <r xlink:href="/rest/r/mail_imapv2"/>',
                        '      <dc>pear.php.net</dc>',
                        '      <dp> Mail_IMAPv2</dp>',
                        '    </p>',
                        '    <a>',
                        '      <r><v>0.2.1</v><s>beta</s></r>',
                        '      <r><v>0.2.0</v><s>beta</s></r>',
                        '    </a>',
                        '    <deps>',
                        '      <v>0.2.1</v>',
                        '      <d>' . serialize($dependenciesDefinition) . '</d>',
                        '    </deps>',
                        '  </pi>',
                        '</f>',
                    ],
                ),
            ],
        ];
    }
}
