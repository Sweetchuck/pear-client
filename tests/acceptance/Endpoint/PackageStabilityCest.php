<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Acceptance\Endpoint;

use Sweetchuck\PearClient\Test\AcceptanceTester;
use Codeception\Example;

/**
 * @group packages
 */
class PackageStabilityCest
{
    protected function casesStabilityGetSuccess(): array
    {
        return $this->casesLatestGetSuccess()
            + $this->casesStableGetSuccess()
            + $this->casesBetaGetSuccess()
            + $this->casesAlphaGetSuccess()
            + $this->casesDevelGetSuccess();
    }

    protected function casesLatestGetSuccess(): array
    {
        return [
            'latest.Console_Table' => [
                'stability' => 'latest',
                'packageName' => 'Console_Table',
                'expected' => '1.3.1',
            ],
        ];
    }

    protected function casesStableGetSuccess(): array
    {
        return [
            'stable.Console_Table' => [
                'stability' => 'latest',
                'packageName' => 'Console_Table',
                'expected' => '1.3.1',
            ],
        ];
    }

    protected function casesBetaGetSuccess(): array
    {
        return [
            'beta.Console_Table' => [
                'stability' => 'beta',
                'packageName' => 'Console_Table',
                'expected' => '0.8',
            ],
        ];
    }

    protected function casesAlphaGetSuccess(): array
    {
        return [
            'alpha.Console_Table' => [
                'stability' => 'alpha',
                'packageName' => 'Console_Table',
                'expected' => null,
            ],
        ];
    }

    protected function casesDevelGetSuccess(): array
    {
        return [
            'devel.Console_Table' => [
                'stability' => 'devel',
                'packageName' => 'Console_Table',
                'expected' => null,
            ],
        ];
    }

    /**
     * @dataProvider casesStabilityGetSuccess
     */
    public function callStabilityGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package stability: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageStabilityGet(
            $example['stability'],
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }

    /**
     * @dataProvider casesLatestGetSuccess
     */
    public function callLatestGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package version: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageLatestGet(
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }

    /**
     * @dataProvider casesStableGetSuccess
     */
    public function callStableGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package version: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageStableGet(
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }

    /**
     * @dataProvider casesBetaGetSuccess
     */
    public function callBetaGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package version: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageBetaGet(
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }

    /**
     * @dataProvider casesAlphaGetSuccess
     */
    public function callAlphaGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package version: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageAlphaGet(
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }

    /**
     * @dataProvider casesDevelGetSuccess
     */
    public function callDevelGetSuccess(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("fetch package version: {$example['stability']} {$example['packageName']}");
        $response = $I->grabPackageDevelGet(
            $example['packageName'],
            $example['options'] ?? [],
        );
        $I->assertSame($example['expected'], $response);
    }
}
