<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group packages
 */
class PackageInfoCest
{
    protected function casesPackageInfoGet(): array
    {
        return [
            'basic' => [
                'packageName' => 'Console_Table',
                'expected' => [
                    'name' => 'Console_Table',
                    'channel' => 'pear.php.net',
                    'category' => [
                        'channel' => '',
                        'name' => 'Console',
                        'alias' => '',
                        'description' => '',
                        'href' => '',
                    ],
                    'license' => 'BSD',
                    'summary' => 'Library that makes it easy to build console style tables',
                    'description' => implode(
                        ' ',
                        [
                            'Provides a Console_Table class with methods such as addRow(), insertRow(), addCol() etc.',
                            'to build console tables with or without headers and with user defined table rules,',
                            'padding, and alignment.'
                        ],
                    ),
                    'releases' => [],
                    'dependencies' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesPackageInfoGet
     */
    public function callPackageInfoGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch info about package: {$example['packageName']}");
        $response = $I->grabPackageInfoGet($example['packageName'], $example['options'] ?? []);
        $I->assertSame($example['expected'], $response->jsonSerialize());
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
