<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient;

use Sweetchuck\PearClient\DataType\CategoryList;
use Sweetchuck\PearClient\DataType\Category;
use Sweetchuck\PearClient\DataType\Maintainer;
use Sweetchuck\PearClient\DataType\MaintainerList;
use Sweetchuck\PearClient\DataType\Package;
use Sweetchuck\PearClient\DataType\PackageList;
use Sweetchuck\PearClient\DataType\PackageMaintainersList;
use Sweetchuck\PearClient\DataType\PackageNameList;
use Sweetchuck\PearClient\DataType\ReleaseList;

/**
 * @link https://pear.php.net/manual/en/core.rest.php
 */
interface PearClientInterface
{

    /**
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @link https://pear.php.net/rest/c/categories.xml
     */
    public function categoriesGet(array $options = []): CategoryList;

    /**
     * @link https://pear.php.net/rest/c/XML/info.xml
     */
    public function categoryGet(string $categoryName, array $options = []): Category;

    /**
     * @link https://pear.php.net/rest/c/XML/packages.xml
     */
    public function categoryPackagesGet(string $categoryName, array $options = []): PackageList;

    /**
     * @link https://pear.php.net/rest/c/XML/packagesinfo.xml
     */
    public function categoryPackagesInfoGet(string $categoryName, array $options = []): PackageList;

    /**
     * @link https://pear.php.net/rest/m/allmaintainers.xml
     */
    public function maintainersGet(array $options = []): MaintainerList;

    /**
     * @link https://pear.php.net/rest/m/adaniel/info.xml
     */
    public function maintainerGet(string $maintainerName, array $options = []): Maintainer;

    /**
     * @link https://pear.php.net/rest/p/packages.xml
     */
    public function packagesGet(array $options = []): PackageNameList;

    /**
     * @link https://pear.php.net/rest/p/archive_tar/info.xml
     */
    public function packageInfoGet(string $packageName, array $options = []): Package;

    /**
     * @link https://pear.php.net/rest/p/archive_zip/maintainers.xml
     */
    public function packageMaintainersGet(string $packageName, array $options = []): PackageMaintainersList;

    /**
     * @link https://pear.php.net/rest/p/archive_zip/maintainers2.xml
     */
    public function packageMaintainers2Get(string $packageName, array $options = []): PackageMaintainersList;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/allreleases.xml
     */
    public function packageAllReleasesGet(string $packageName, array $options = []): ReleaseList;

    /**
     * Wrapper for https://pecl.php.net/rest/r/xdebug/<STABILITY>.txt
     */
    public function packageStabilityGet(string $stability, string $packageName, array $options = []): ?string;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/latest.txt
     */
    public function packageLatestGet(string $packageName, array $options = []): ?string;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/stable.txt
     */
    public function packageStableGet(string $packageName, array $options = []): ?string;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/beta.txt
     */
    public function packageBetaGet(string $packageName, array $options = []): ?string;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/alpha.txt
     */
    public function packageAlphaGet(string $packageName, array $options = []): ?string;

    /**
     * @link https://pecl.php.net/rest/r/xdebug/devel.txt
     */
    public function packageDevelGet(string $packageName, array $options = []): ?string;
}
