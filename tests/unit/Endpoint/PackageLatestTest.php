<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 *
 * @group releases
 */
class PackageLatestTest extends TestBase
{

    protected string $method = 'packageLatestGet';

    public function casesGetSuccess(): array
    {
        $uriBase = 'https://127.0.0.1/rest';
        $packageName = 'xdebug';
        $stability = 'latest';

        return [
            'xdebug.latest.200' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/$packageName/$stability.txt",
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
                ['xdebug'],
            ],
            'xdebug.latest.404' => [
                [
                    'requests' => [
                        [
                            'uri' => "$uriBase/r/$packageName/$stability.txt",
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
                ['xdebug'],
            ],
        ];
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
