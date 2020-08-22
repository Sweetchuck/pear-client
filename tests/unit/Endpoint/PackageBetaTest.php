<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

/**
 * @covers \Sweetchuck\PearClient\PearClient<extended>
 *
 * @group releases
 */
class PackageBetaTest extends TestBase
{

    protected string $method = 'packageBetaGet';

    public function casesGetSuccess(): array
    {
        $uriBase = 'https://127.0.0.1/rest';
        $packageName = 'xdebug';
        $stability = 'beta';

        return [
            "$packageName.$stability.200" => [
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
                [$packageName],
            ],
            "$packageName.$stability.404" => [
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
                            'HTTP/1.1 404 Not Found' => '',
                            'Content-Type' => 'text/plain',
                        ],
                        'body' => "3.0.0RC1\n",
                    ],
                ],
                [],
                [$packageName],
            ],
        ];
    }

    public function casesGetFail(): array
    {
        return [];
    }
}
