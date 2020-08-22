<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit\Parser;

use DOMDocument;
use Sweetchuck\PearClient\Parser\Base as ParserBase;
use Sweetchuck\PearClient\Tests\Unit\TestBase;

abstract class ParserTestBase extends TestBase
{

    protected string $parserClass = '';

    abstract public function casesParse(): array;

    /**
     * @dataProvider casesParse
     */
    public function testParse(array $expected, string $xml): void
    {
        $parser = $this->createInstance();

        $doc = new DOMDocument();
        $this->tester->assertTrue($doc->loadXML($xml, LIBXML_NONET));
        $this->tester->assertSame($expected, $parser->parse($doc));
    }

    protected function createInstance(): ParserBase
    {
        return new $this->parserClass();
    }
}
