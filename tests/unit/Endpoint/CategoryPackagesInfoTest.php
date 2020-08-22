<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\PackageList;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Package<extended>
 * @covers \Sweetchuck\PearClient\DataType\PackageList<extended>
 *
 * @group categories
 */
class CategoryPackagesInfoTest extends TestBase
{

    protected string $method = 'categoryPackagesInfoGet';

    public function casesGetSuccess(): array
    {
        $uriPattern = 'https://127.0.0.1/rest/c/{categoryName}/packagesinfo.xml';
        $cases = [];
        foreach ($this->getResponseXmlFiles('get') as $xmlFile) {
            $ymlFile = preg_replace('/\.response\.xml$/', '.parsed.yml', $xmlFile->getPathname());
            $id = $xmlFile->getBasename();
            $cases[$id] = [
                [
                    'requests' => [
                        [
                            'uri' => strtr($uriPattern, ['{categoryName}' => 'XML']),
                        ],
                    ],
                    'return' => PackageList::__set_state(Yaml::parseFile($ymlFile)),
                ],
                [
                    [
                        'body' => file_get_contents($xmlFile->getPathname()),
                    ],
                ],
                [],
                [
                    'XML',
                ],
            ];
        }

        return $cases;
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
