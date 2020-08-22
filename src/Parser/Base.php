<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Sweetchuck\PearClient\ParserInterface;
use Sweetchuck\PearClient\Utils;

abstract class Base implements ParserInterface
{
    protected ?DOMDocument $doc;

    protected ?DOMXPath $xpath;

    protected array $maintainerMapping = [
        'h' => 'name',
        'n' => 'displayName',
        'u' => 'homepage',
        'a' => 'status',
        'r' => 'role',
    ];

    protected array $releaseMapping = [
        'v' => 'version',
        's' => 'stability',
    ];

    protected array $data = [];

    public function parse(DOMDocument $doc): array
    {
        $this->doc = $doc;

        $data = $this
            ->init()
            ->parseDoIt()
            ->data;

        $this->reset();

        return $data;
    }

    protected function init()
    {
        $this->data = [];
        $this->xpath = new DOMXPath($this->doc);

        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        if ($root && $root->hasAttribute('xmlns')) {
            $this->xpath->registerNamespace(
                'pear',
                $root->getAttribute('xmlns'),
            );
        }

        return $this;
    }

    /**
     * @return $this
     */
    abstract protected function parseDoIt();

    protected function parseTextElements(array $mapping, DOMElement $parent): array
    {
        $values = [];
        foreach ($mapping as $tagName => $dst) {
            $elements = $parent->getElementsByTagName($tagName);
            if (!$elements->count()) {
                continue;
            }

            /** @var \DOMElement $element */
            $element = $elements->item(0);
            $value = trim($element->textContent);
            if (mb_strlen($value) > 0) {
                $values[$dst] = $value;
            }
        }

        return $values;
    }

    protected function parseCategory(DOMElement $element): array
    {
        $category = [
            'name' => trim($element->textContent),
        ];

        if ($element->hasAttribute('xlink:href')) {
            $package['category']['href'] = $element->getAttribute('xlink:href');
        }

        return $category;
    }

    protected function parseRelease(DOMElement $element): array
    {
        return $this->parseTextElements($this->releaseMapping, $element);
    }

    /**
     * @return $this
     */
    protected function reset()
    {
        $this->doc = null;
        $this->xpath = null;
        $this->data = [];

        return $this;
    }
}
