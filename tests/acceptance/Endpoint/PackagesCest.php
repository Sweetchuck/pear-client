<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group packages
 */
class PackagesCest
{
    protected function casesPackagesGet(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'list' => [
                        'Console_Table',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesPackagesGet
     */
    public function callPackagesGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo('fetch all the package names');
        $response = $I->grabPackagesGet($example['options'] ?? []);
        foreach ($example['expected']['list'] as $value) {
            $I->assertContains($value, $response->list);
        }
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
