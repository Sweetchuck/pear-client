<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\DataType\Category;
use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group categories
 */
class CategoriesCest
{
    protected function casesCategoriesGet(): array
    {
        return [
            'basic' => [
                'params' => [],
            ],
            'take' => [
                'params' => [
                    'query' => [
                        '$top' => 2,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesCategoriesGet
     */
    public function callCategoriesGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo('retrieve all the categories');
        $response = $I->grabCategoriesGet($example['params'] ?? []);
        $I->assertSame(
            $response->channel,
            'pear.php.net',
        );
        $I->assertGreaterThan(
            3,
            count($response->list),
            'response.categories.count greater than 3'
        );
        $I->assertInstanceOf(
            Category::class,
            reset($response->list),
            'Items in the response.categories are DataType\Category instances',
        );

        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
