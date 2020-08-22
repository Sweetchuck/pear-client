<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group categories
 */
class CategoryCest
{
    protected function casesCategoryGet(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'channel' => 'pear.php.net',
                    'name' => 'Audio',
                    'alias' => 'Audio',
                    'description' => 'Audio',
                    'href' => '',
                ],
                'categoryName' => 'Audio',
                'options' => [],
            ],
        ];
    }

    /**
     * @dataProvider casesCategoryGet
     */
    public function callCategoryGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch details about the {$example['categoryName']} category");
        $response = $I->grabCategoryGet($example['categoryName'], $example['options'] ?? []);

        $I->assertSame($example['expected'], $response->jsonSerialize());
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
