<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use Sweetchuck\PearClient\Utils;

/**
 * @link https://pear.php.net/rest/c/Mail/info.xml
 * @see \Sweetchuck\PearClient\DataType\Category
 */
class CategoryParser extends Base
{

    protected function parseDoIt()
    {
        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        $mapping = [
            'c' => 'channel',
            'n' => 'name',
            'a' => 'alias',
            'd' => 'description',
        ];
        $this->data += $this->parseTextElements($mapping, $root);

        return $this;
    }
}
