<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\PackageMaintainersList;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Package<extended>
 * @covers \Sweetchuck\PearClient\DataType\PackageList<extended>
 *
 * @group packages
 */
class PackageMaintainers2Test extends TestBase
{

    protected string $method = 'packageMaintainers2Get';

    public function casesGetSuccess(): array
    {
        $uri = 'https://127.0.0.1/rest/p/archive_zip/maintainers2.xml';
        $cases = [];
        foreach ($this->getResponseXmlFiles('get') as $xmlFile) {
            $ymlFile = preg_replace('/\.response\.xml$/', '.parsed.yml', $xmlFile->getPathname());
            $id = $xmlFile->getBasename();
            $cases[$id] = [
                [
                    'requests' => [
                        [
                            'uri' => $uri,
                        ],
                    ],
                    'return' => PackageMaintainersList::__set_state(Yaml::parseFile($ymlFile)),
                ],
                [
                    [
                        'body' => file_get_contents($xmlFile->getPathname()),
                    ],
                ],
                [],
                [
                    'archive_zip',
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
