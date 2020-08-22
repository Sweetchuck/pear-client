<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group categories
 */
class CategoryPackagesCest
{
    protected function casesCategoryPackagesGet(): array
    {
        return [
            'Audio' => [
                'expected' => [
                    'list' => [],
                ],
                'categoryName' => 'Audio',
                'options' => [],
            ],
            'XML' => [
                'expected' => [
                    'list' => [
                        'XML_Parser' => [
                            'name' => 'XML_Parser',
                            'href' => '/rest/p/xml_parser',
                            'releases' => [],
                            'dependencies' => [],
                        ],
                    ],
                ],
                'categoryName' => 'XML',
                'options' => [],
            ],
        ];
    }

    /**
     * @dataProvider casesCategoryPackagesGet
     */
    public function callCategoryPackagesGet(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch details about the {$example['categoryName']} category");
        $response = $I->grabCategoryPackagesGet($example['categoryName'], $example['options'] ?? []);

        foreach ($example['expected']['list'] as $key => $value) {
            $I->assertSame($value, $response->list[$key]->jsonSerialize());
        }
        $I->doNotSeeAnyAdditionalProperties($response, '$response');
    }
}
