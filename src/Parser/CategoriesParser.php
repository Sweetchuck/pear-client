<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

/**
 * @link https://pear.php.net/rest/c/categories.xml
 * @see \Sweetchuck\PearClient\DataType\CategoryList
 */
class CategoriesParser extends Base
{
    protected function parseDoIt()
    {
        return $this
            ->parseChannel()
            ->parseCategories();
    }

    protected function parseChannel()
    {
        $elements = $this->xpath->query('/pear:a/pear:ch');
        if (!$elements->count()) {
            return $this;
        }

        $this->data['channel'] = trim($elements->item(0)->textContent);

        return $this;
    }

    protected function parseCategories()
    {
        $this->data['list'] = [];

        /** @var \DOMElement $element */
        foreach ($this->xpath->query('/pear:a/pear:c', null, false) as $element) {
            $c = [];
            if ($element->hasAttribute('xlink:href')) {
                $c['href'] = $element->getAttribute('xlink:href');
            }

            $name = trim($element->textContent);
            if (mb_strlen($name)) {
                $c['name'] = $name;
            }

            if (!$c) {
                continue;
            }

            $this->data['list'][] = $c;
        }

        return $this;
    }
}
