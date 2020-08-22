<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\PackageParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\PackageParser<extended>
 */
class PackageParserTest extends ParserTestBase
{

    protected string $parserClass = PackageParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.package',
            'http://pear.php.net/dtd/rest.package.xsd',
        ]);

        $rootElementAttributes = implode(' ', [
            'xmlns="http://pear.php.net/dtd/rest.package"',
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            'xmlns:xlink="http://www.w3.org/1999/xlink"',
            "xsi:schemaLocation=\"$xsiSchemaLocation\"",
        ]);

        return [
            'all' => [
                [
                    'name' => 'Archive_Zip',
                    'channel' => 'pear.php.net',
                    'license' => 'LGPL',
                    'summary' => 'Zip file archiving management class',
                    'description' => 'my_desc',
                    'category' => [
                        'name' => 'File Formats',
                    ],
                    'deprecatedInFavorOf' => [
                        'name' => 'zip',
                        'channel' => 'pecl.php.net',
                    ],
                ],
                implode(
                    "\n",
                    [
                        '<?xml version="1.0" encoding="UTF-8" ?>',
                        "<p $rootElementAttributes>",
                        '  <n>Archive_Zip</n>',
                        '  <c>pear.php.net</c>',
                        '  <ca xlink:href="/rest/c/File+Formats">File Formats</ca>',
                        '  <l>LGPL</l>',
                        '  <s>Zip file archiving management class</s>',
                        '  <d>my_desc</d>',
                        '  <r xlink:href="/rest/r/archive_zip"/>',
                        '  <dc>pecl.php.net</dc>',
                        '  <dp>zip</dp>',
                        '</p>',
                    ],
                ),
            ],
        ];
    }
}
