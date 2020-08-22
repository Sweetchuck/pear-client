<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use Sweetchuck\PearClient\Parser\AllPackagesParser;

/**
 * @covers \Sweetchuck\PearClient\Parser\AllPackagesParser<extended>
 */
class AllPackagesParserTest extends ParserTestBase
{

    protected string $parserClass = AllPackagesParser::class;

    public function casesParse(): array
    {
        $xsiSchemaLocation = implode(' ', [
            'http://pear.php.net/dtd/rest.allpackages',
            'http://pear.php.net/dtd/rest.allpackages.xsd',
        ]);

        return [
            'channel' => [
                [
                    'channel' => 'pear.php.net',
                    'list' => [],
                ],
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8" ?>',
                    '<a',
                    '    xmlns="http://pear.php.net/dtd/rest.allpackages"',
                    '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                    '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                    "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                    '',
                    '    <c>pear.php.net</c>',
                    '</a>',
                ]),
            ],
            'packageNames' => [
                [
                    'list' => [
                        'Archive_Tar',
                        'Archive_Zip',
                        'Auth',
                    ],
                ],
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8" ?>',
                    '<a',
                    '    xmlns="http://pear.php.net/dtd/rest.allpackages"',
                    '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                    '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                    "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                    '',
                    '    <p>Archive_Tar</p>',
                    '    <p>Archive_Zip</p>',
                    '    <p>Auth</p>',
                    '</a>',
                ]),
            ],
            'all' => [
                [
                    'channel' => 'pear.php.net',
                    'list' => [
                        'Archive_Tar',
                        'Archive_Zip',
                        'Auth',
                    ],
                ],
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8" ?>',
                    '<a',
                    '    xmlns="http://pear.php.net/dtd/rest.allpackages"',
                    '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                    '    xmlns:xlink="http://www.w3.org/1999/xlink"',
                    "    xsi:schemaLocation=\"$xsiSchemaLocation\">",
                    '',
                    '    <c>pear.php.net</c>',
                    '    <p>Archive_Tar</p>',
                    '    <p>Archive_Zip</p>',
                    '    <p>Auth</p>',
                    '</a>',
                ]),
            ],
        ];
    }
}
