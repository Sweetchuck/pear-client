<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

/**
 * @link https://pear.php.net/rest/p/packages.xml
 * @see \Sweetchuck\PearClient\DataType\PackageNameList
 */
class AllPackagesParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        /** @var \DOMElement $element */
        $cTags = $this->doc->getElementsByTagName('c');
        if ($cTags->count()) {
            $this->data['channel'] = trim($cTags->item(0)->textContent);
        }

        $this->data['list'] = [];
        foreach ($this->doc->getElementsByTagName('p') as $element) {
            $this->data['list'][] = trim($element->textContent);
        }

        return $this;
    }
}
