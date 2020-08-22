<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use DOMElement;

/**
 * @link https://pear.php.net/rest/c/Mail/packagesinfo.xml
 * @see \Sweetchuck\PearClient\DataType\PackageList
 */
class CategoryPackageInfoParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        /** @var \DOMElement $piElement */
        foreach ($this->doc->getElementsByTagName('pi') as $piElement) {
            $package = $this->parsePackageInfo($piElement);
            if ($package) {
                $this->data['list'][$package['name']] = $package;
            }
        }

        return $this;
    }

    protected function parsePackageInfo(DOMElement $element): array
    {
        $package = [];
        $subElements = $this->xpath->query('./pear:p', $element, false);
        /** @var \DOMElement $subElement */
        if ($subElements->count()) {
            $subElement = $subElements->item(0);
            $package += $this->parsePackage($subElement);
        }

        $subElements = $this->xpath->query('./pear:a', $element);
        if ($subElements->count()) {
            $subElement = $subElements->item(0);
            $values = $this->parseReleases($subElement);
            if ($values) {
                $package['releases'] = $values;
            }
        }

        $subElements = $this->xpath->query('./pear:deps', $element);
        foreach ($subElements as $subElement) {
            $values = $this->parseDependency($subElement);
            if ($values) {
                $package['dependencies'][$values['version']] = $values;
            }
        }

        return $package;
    }

    protected function parsePackage(DOMElement $element): array
    {
        $package = [];
        $mapping = [
            'n' => 'name',
            'c' => 'channel',
            'l' => 'license',
            's' => 'summary',
            'd' => 'description',
            'dc' => 'channel',
            'dp' => 'name',
        ];
        /** @var \DOMElement $sub */
        foreach ($this->xpath->query('./pear:*', $element) as $sub) {
            switch ($sub->tagName) {
                case 'n':
                case 'c':
                case 'l':
                case 's':
                case 'd':
                    $package[$mapping[$sub->tagName]] = trim($sub->textContent);
                    break;

                case 'ca':
                    $package['category'] = $this->parseCategory($sub);
                    break;

                case 'r':
                    if ($sub->hasAttribute('xlink:href')) {
                        $package['href'] = $sub->getAttribute('xlink:href');
                    }
                    break;

                case 'dc':
                case 'dp':
                    $package['deprecatedInFavorOf'][$mapping[$sub->tagName]] = trim($sub->textContent);
                    break;
            }
        }

        return $package;
    }

    protected function parseReleases(DOMElement $element): array
    {
        $releases = [];
        /** @var \DOMElement $sub */
        foreach ($this->xpath->query('./pear:r', $element) as $sub) {
            $release = $this->parseRelease($sub);
            if ($release) {
                $releases[$release['version']] = $release;
            }
        }

        return $releases;
    }

    protected function parseRelease(DOMElement $element): array
    {
        $release = [];
        $mapping = [
            'v' => 'version',
            's' => 'stability',
        ];
        /** @var \DOMElement $sub */
        foreach ($this->xpath->query('./pear:*', $element) as $sub) {
            $release[$mapping[$sub->tagName]] = trim($sub->textContent);
        }

        return $release;
    }

    protected function parseDependency(DOMElement $element): array
    {
        $dependency = [];
        /** @var \DOMElement $sub */
        foreach ($this->xpath->query('./pear:*', $element) as $sub) {
            switch ($sub->tagName) {
                case 'v':
                    $dependency['version'] = trim($sub->textContent);
                    break;

                case 'd':
                    $definitions = unserialize(trim($sub->textContent));
                    if ($definitions !== false) {
                        $dependency['definitions'] = $definitions;
                    }
                    break;
            }
        }

        return $dependency;
    }
}
