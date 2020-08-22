<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group maintainers
 */
class MaintainersCest
{
    protected function casesMaintainersGet(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'list' => [
                        'aashley' => [
                            'href' => '/rest/m/aashley',
                            'name' => 'aashley',
                            'displayName' => '',
                            'homepage' => '',
                            'role' => '',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesMaintainersGet
     */
    public function callMaintainersGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo('fetch details about all the maintainers');
        $response = $I->grabMaintainersGet($example['options'] ?? []);

        foreach ($example['expected']['list'] as $key => $value) {
            $I->assertSame($value, $response->list[$key]->jsonSerialize());
        }
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
