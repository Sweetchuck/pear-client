<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Test\Helper;

use Sweetchuck\PearClient\DataType\Category;
use Sweetchuck\PearClient\DataType\CategoryList;
use Sweetchuck\PearClient\DataType\Maintainer;
use Sweetchuck\PearClient\DataType\MaintainerList;
use Sweetchuck\PearClient\DataType\Package;
use Sweetchuck\PearClient\DataType\PackageList;
use Sweetchuck\PearClient\DataType\PackageMaintainersList;
use Sweetchuck\PearClient\DataType\PackageNameList;
use Sweetchuck\PearClient\DataType\ReleaseList;
use Sweetchuck\PearClient\PearClient;
use Sweetchuck\PearClient\PearClientInterface;
use Codeception\Module;

class Acceptance extends Module
{

    /**
     * @var string[]
     */
    protected $requiredFields = [
        'baseUri',
    ];

    protected PearClientInterface $client;

    /**
     * @inheritdoc
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->_initializeClient();
    }

    protected function _initializeClient()
    {
        $config = $this->_getConfig();
        $this->client = new PearClient(PearClient::createHttpClient($config));

        return $this;
    }

    public function grabCategoriesGet(array $options = []): CategoryList
    {
        return $this->client->categoriesGet($options);
    }

    public function grabCategoryGet(string $categoryName, array $options = []): Category
    {
        return $this->client->categoryGet($categoryName, $options);
    }

    public function grabCategoryPackagesGet(string $categoryName, array $options = []): PackageList
    {
        return $this->client->categoryPackagesGet($categoryName, $options);
    }

    public function grabCategoryPackagesInfoGet(string $categoryName, array $options = []): PackageList
    {
        return $this->client->categoryPackagesInfoGet($categoryName, $options);
    }

    public function grabMaintainersGet(array $options = []): MaintainerList
    {
        return $this->client->maintainersGet($options);
    }

    public function grabMaintainerGet(string $maintainerName, array $options = []): Maintainer
    {
        return $this->client->maintainerGet($maintainerName, $options);
    }

    public function grabPackagesGet(array $options = []): PackageNameList
    {
        return $this->client->packagesGet($options);
    }

    public function grabPackageInfoGet(string $packageName, array $options = []): Package
    {
        return $this->client->packageInfoGet($packageName, $options);
    }

    public function grabPackageMaintainersGet(string $packageName, array $options = []): PackageMaintainersList
    {
        return $this->client->packageMaintainersGet($packageName, $options);
    }

    public function grabPackageAllReleasesGet(string $packageName, array $options = []): ReleaseList
    {
        return $this->client->packageAllReleasesGet($packageName, $options);
    }

    public function grabPackageStabilityGet(string $stability, string $packageName, array $options = []): ?string
    {
        return $this->client->packageStabilityGet($stability, $packageName, $options);
    }

    public function grabPackageLatestGet(string $packageName, array $options = []): ?string
    {
        return $this->client->packageLatestGet($packageName, $options);
    }

    public function grabPackageStableGet(string $packageName, array $options = []): ?string
    {
        return $this->client->packageStableGet($packageName, $options);
    }

    public function grabPackageBetaGet(string $packageName, array $options = []): ?string
    {
        return $this->client->packageBetaGet($packageName, $options);
    }

    public function grabPackageAlphaGet(string $packageName, array $options = []): ?string
    {
        return $this->client->packageAlphaGet($packageName, $options);
    }

    public function grabPackageDevelGet(string $packageName, array $options = []): ?string
    {
        return $this->client->packageDevelGet($packageName, $options);
    }
}
