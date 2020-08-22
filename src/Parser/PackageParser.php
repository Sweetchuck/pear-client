<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use Sweetchuck\PearClient\Utils;

/**
 * @link https://pear.php.net/rest/p/archive_zip/info.xml
 * @see \Sweetchuck\PearClient\DataType\Package
 */
class PackageParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        $mapping = [
            'n' => 'name',
            'c' => 'channel',
            'l' => 'license',
            's' => 'summary',
            'd' => 'description',
        ];

        $this->data += $this->parseTextElements($mapping, $root);
        $this->parseCategoryElement();
        $this->parseDeprecated();

        return $this;
    }

    protected function parseCategoryElement()
    {
        $elements = $this->doc->getElementsByTagName('ca');
        if ($elements->count()) {
            /** @var \DOMElement $element */
            $element = $elements->item(0);
            $this->data['category'] = $this->parseCategory($element);
        }

        return $this;
    }

    protected function parseDeprecated()
    {
        $data = $this->parseTextElements(
            [
                'dp' => 'name',
                'dc' => 'channel',
            ],
            Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes),
        );

        if ($data) {
            $this->data['deprecatedInFavorOf'] = $data;
        }

        return $this;
    }
}
