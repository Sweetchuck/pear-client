<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use Sweetchuck\PearClient\Utils;

/**
 * @link https://pecl.php.net/rest/r/xdebug/allreleases.xml
 * @see \Sweetchuck\PearClient\DataType\ReleaseList
 */
class AllReleasesParser extends Base
{

    /**
     * {@inheritdoc}
     */
    protected function parseDoIt()
    {
        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        $this->data += $this->parseTextElements(
            [
                'c' => 'channel',
                'p' => 'packageName',
            ],
            $root,
        );
        $this->data['list'] = [];
        /** @var \DOMElement $element */
        foreach ($root->getElementsByTagName('r') as $element) {
            $release = $this->parseRelease($element);
            if (!empty($release['version'])) {
                $this->data['list'][$release['version']] = $release;
            }
        }

        return $this;
    }
}
