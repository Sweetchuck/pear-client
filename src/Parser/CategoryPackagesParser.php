<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use DOMElement;

/**
 * @link https://pear.php.net/rest/c/Mail/packages.xml
 * @see \Sweetchuck\PearClient\DataType\PackageList
 */
class CategoryPackagesParser extends Base
{

    protected function parseDoIt()
    {
        /** @var \DOMElement $pElement */
        foreach ($this->doc->getElementsByTagName('p') as $pElement) {
            $p = $this->parseElement($pElement);
            if ($p) {
                $this->data['list'][$p['name']] = $p;
            }
        }

        return $this;
    }

    protected function parseElement(DOMElement $element): ?array
    {
        $p = [];
        if ($element->hasAttribute('xlink:href')) {
            $p['href'] = $element->getAttribute('xlink:href');
        }

        $name = trim($element->textContent);
        if (mb_strlen($name)) {
            $p['name'] = $name;
        }

        return $p ?: null;
    }
}
