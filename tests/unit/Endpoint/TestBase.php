<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Endpoint;

use Codeception\Test\Unit;
use Sweetchuck\PearClient\Test\UnitTester;
use Symfony\Component\Finder\Finder;

abstract class TestBase extends Unit
{
    protected UnitTester $tester;

    protected string $method;

    /**
     * @return $this
     */
    public function expectExceptionAllInOne(array $conditions)
    {
        foreach ($conditions as $key => $condition) {
            switch ($key) {
                case 'class':
                    $this->expectException($condition);
                    break;

                case 'message':
                    $this->expectExceptionMessage($condition);
                    break;

                case 'code':
                    $this->expectExceptionCode($condition);
                    break;

                case 'messageMatches':
                    if (is_string($condition)) {
                        $this->expectExceptionMessageMatches($condition);

                        break;
                    }

                    foreach ($conditions as $pattern) {
                        $this->expectExceptionMessageMatches($pattern);
                    }
                    break;
            }
        }

        return $this;
    }

    abstract public function casesGetSuccess(): array;

    abstract public function casesGetFail(): array;

    /**
     * @dataProvider casesGetSuccess
     * @dataProvider casesGetFail
     */
    public function testGet(
        array $expected,
        array $responses,
        array $clientOptions = [],
        array $methodArgs = []
    ): void {
        $container =& $this->tester->initClient($responses, $clientOptions);

        $defaultRequest = [
            'method' => 'GET',
            'headers' => $this->tester->getDefaultGetRequestHeaders(),
        ];

        if (!empty($expected['throwable'])) {
            $this->tester->expectThrowable(
                $expected['throwable'],
                function () use (&$return, $methodArgs) {
                    $return = $this->tester->client->{$this->method}(...$methodArgs);
                },
            );
        } else {
            $this->tester->assertEquals(
                $expected['return'],
                $this->tester->client->{$this->method}(...$methodArgs),
            );
        }

        $this->tester->assertCount(count($expected['requests']), $container);
        foreach ($container as $i => $transaction) {
            $expectedRequest = $expected['requests'][$i] + $defaultRequest;
            $this->tester->assertHttpRequest($expectedRequest, $transaction['request']);
        }
    }

    /**
     * @return iterable|\Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getResponseXmlFiles(string $method): iterable
    {
        $endpoint = preg_replace('/(Get|Post)$/', '', $this->method);
        $casesDir = codecept_data_dir("fixtures/endpoint/$endpoint");

        return (new Finder())
            ->in($casesDir)
            ->files()
            ->name("$method.*.response.xml");
    }
}
