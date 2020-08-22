<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group packages
 */
class PackageAllReleasesCest
{
    protected function casesGetSuccess(): array
    {
        return [
            'basic' => [
                'packageName' => 'Console_Table',
                'expected' => [
                    'list' => [
                        '1.0' => [
                            'version' => '1.0'      ,
                            'stability' => 'stable',
                        ],
                        '0.8' => [
                            'version' => '0.8',
                            'stability' => 'beta',
                        ],
                    ],
                    'channel' => 'pear.php.net',
                    'packageName' => 'Console_Table',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetSuccess
     */
    public function callGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch all releases of a package: {$example['packageName']}");
        $response = $I->grabPackageAllReleasesGet($example['packageName'], $example['options'] ?? []);
        foreach ($example['expected']['list'] as $key => $value) {
            $I->assertSame($value, $response->list[$key]->jsonSerialize());
        }
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
