<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Sweetchuck\PearClient\DataType\MaintainerList;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 * @covers \Sweetchuck\PearClient\DataType\Maintainer<extended>
 * @covers \Sweetchuck\PearClient\DataType\MaintainerList<extended>
 *
 * @group maintainers
 */
class MaintainersTest extends TestBase
{

    protected string $method = 'maintainersGet';

    public function casesGetSuccess(): array
    {
        $uri = 'https://127.0.0.1/rest/m/allmaintainers.xml';
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
                    'return' => MaintainerList::__set_state(Yaml::parseFile($ymlFile)),
                ],
                [
                    [
                        'body' => file_get_contents($xmlFile->getPathname()),
                    ],
                ],
                [],
                [],
            ];
        }

        return $cases;
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
