<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use Sweetchuck\PearClient\Utils;

/**
 * @link https://pear.php.net/rest/m/adaniel/info.xml
 * @see \Sweetchuck\PearClient\DataType\Maintainer
 */
class MaintainerParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        $this->data += $this->parseTextElements($this->maintainerMapping, $root);

        return $this;
    }
}
