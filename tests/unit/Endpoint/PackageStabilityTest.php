<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 *
 * @group releases
 */
class PackageStabilityTest extends TestBase
{

    protected string $method = 'packageStabilityGet';

    public function casesGetSuccess(): array
    {
        $uriBase = 'https://127.0.0.1/rest';

        return [
            'xdebug.latest' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/xdebug/latest.txt",
                        ],
                    ],
                    'return' => '3.0.3',
                ],
                [
                    [
                        'headers' => [
                            'Content-Type' => 'text/plain',
                        ],
                        'body' => "3.0.3\n",
                    ],
                ],
                [],
                ['latest', 'xdebug'],
            ],
            'xdebug.stable' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/xdebug/stable.txt",
                        ],
                    ],
                    'return' => '3.0.3',
                ],
                [
                    [
                        'headers' => [
                            'Content-Type' => 'text/plain',
                        ],
                        'body' => "3.0.3\n",
                    ],
                ],
                [],
                ['stable', 'xdebug'],
            ],
            'xdebug.beta' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/xdebug/beta.txt",
                        ],
                    ],
                    'return' => '3.0.0RC1',
                ],
                [
                    [
                        'headers' => [
                            'Content-Type' => 'text/plain',
                        ],
                        'body' => "3.0.0RC1\n",
                    ],
                ],
                [],
                ['beta', 'xdebug'],
            ],
            'xdebug.404' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/xdebug/beta.txt",
                        ],
                    ],
                    'return' => null,
                ],
                [
                    [
                        'statusCode' => 404,
                        'headers' => [
                            'Content-Type' => 'text/plain',
                        ],
                        'body' => "3.0.0RC1\n",
                    ],
                ],
                [],
                ['beta', 'xdebug'],
            ],
        ];
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
