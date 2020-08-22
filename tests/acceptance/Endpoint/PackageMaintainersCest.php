<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group packages
 */
class PackageMaintainersCest
{
    protected function casesGetSuccess(): array
    {
        return [
            'basic' => [
                'packageName' => 'Console_Table',
                'expected' => [
                    'list' => [
                        'yunosh' => [
                            'href' => '',
                            'name' => 'yunosh',
                            'displayName' => '',
                            'homepage' => '',
                            'status' => 1,
                            'role' => '',
                        ],
                        'tal' => [
                            'href' => '',
                            'name' => 'tal',
                            'displayName' => '',
                            'homepage' => '',
                            'status' => 0,
                            'role' => '',
                        ],
                        'xnoguer' => [
                            'href' => '',
                            'name' => 'xnoguer',
                            'displayName' => '',
                            'homepage' => '',
                            'status' => 0,
                            'role' => '',
                        ],
                        'richard' => [
                            'href' => '',
                            'name' => 'richard',
                            'displayName' => '',
                            'homepage' => '',
                            'status' => 0,
                            'role' => '',
                        ],
                    ],
                    'packageName' => 'Console_Table',
                    'channel' => 'pear.php.net',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetSuccess
     */
    public function callGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package maintainers: {$example['packageName']}");
        $response = $I->grabPackageMaintainersGet($example['packageName'], $example['options'] ?? []);
        $I->assertSame($example['expected'], $response->jsonSerialize());
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
