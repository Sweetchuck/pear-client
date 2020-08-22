<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\DataType;

use Sweetchuck\PearClient\DataType\Base;
use Codeception\Test\Unit;
use Sweetchuck\PearClient\Test\UnitTester;

abstract class TestBase extends Unit
{
    protected UnitTester $tester;

    protected string $className = '';

    abstract public function casesImportExport(): array;

    /**
     * @dataProvider casesImportExport
     */
    public function testImportExport(array $expected, array $data)
    {
        $instance = $this->getInstance($data);
        $this->tester->assertSame($expected, $instance->jsonSerialize());
    }

    protected function assertPropertyValue(array $expected, array $data)
    {
        $instance = $this->getInstance($data);
        foreach ($expected as $property => $value) {
            $this->tester->assertEquals(
                $value,
                $instance->{$property},
                "property: $property"
            );
        }
    }

    protected function getInstance(array $data): Base
    {
        $setState = [$this->className, '__set_state'];

        return $setState($data);
    }
}
