<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group maintainers
 */
class MaintainerCest
{
    protected function casesMaintainerGet(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'href' => '',
                    'name' => 'aashley',
                    'displayName' => 'Adam Ashley',
                    'homepage' => 'http://www.adamashley.name/',
                    'role' => '',
                ],
                'maintainerName' => 'aashley',
            ],
        ];
    }

    /**
     * @dataProvider casesMaintainerGet
     */
    public function callMaintainerGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch details about a maintainer: {$example['maintainerName']}");
        $response = $I->grabMaintainerGet($example['maintainerName'], $example['options'] ?? []);

        $I->assertSame($example['expected'], $response->jsonSerialize());
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
