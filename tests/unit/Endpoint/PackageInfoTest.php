<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\Package;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Package<extended>
 *
 * @group packages
 */
class PackageInfoTest extends TestBase
{

    protected string $method = 'packageInfoGet';

    public function casesGetSuccess(): array
    {
        $uri = 'https://127.0.0.1/rest/p/archive_tar/info.xml';
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
                    'return' => Package::__set_state(Yaml::parseFile($ymlFile)),
                ],
                [
                    [
                        'body' => file_get_contents($xmlFile->getPathname()),
                    ],
                ],
                [],
                [
                    'Archive_Tar',
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
