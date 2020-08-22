<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group categories
 */
class CategoryPackagesInfoCest
{
    protected function casesCategoryPackagesInfoGet(): array
    {
        return [
            'Audio' => [
                'expected' => null,
                'categoryName' => 'Audio',
                'options' => [],
            ],
            'XML' => [
                'expected' => 'XML_Parser',
                'categoryName' => 'XML',
                'options' => [],
            ],
        ];
    }

    /**
     * @dataProvider casesCategoryPackagesInfoGet
     */
    public function callCategoryPackagesInfoGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("callCategoryPackagesGet {$example['categoryName']}");
        $response = $I->grabCategoryPackagesInfoGet($example['categoryName'], $example['options'] ?? []);

        if ($example['expected'] === null) {
            $I->assertEmpty($response->list);
        } else {
            $I->assertArrayHasKey($example['expected'], $response->list);
        }
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
