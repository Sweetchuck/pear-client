<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use DOMElement;

/**
 * @link https://pecl.php.net/rest/m/allmaintainers.xml
 * @see \Sweetchuck\PearClient\DataType\MaintainerList
 */
class AllMaintainersParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        $this->data['list'] = [];
        foreach ($this->doc->getElementsByTagName('h') as $element) {
            $m = $this->parseMaintainer($element);
            if ($m) {
                $this->data['list'][$m['name']] = $m;
            }
        }

        return $this;
    }

    protected function parseMaintainer(DOMElement $element): array
    {
        $m = [];
        if ($element->hasAttribute('xlink:href')) {
            $m['href'] = $element->getAttribute('xlink:href');
        }

        $name = trim($element->textContent);
        if ($name) {
            $m['name'] = $name;
        }

        return $m;
    }
}
