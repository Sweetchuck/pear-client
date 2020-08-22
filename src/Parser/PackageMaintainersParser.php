<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Parser;

use Sweetchuck\PearClient\Utils;

/**
 * @link https://pear.php.net/rest/p/archive_zip/maintainers.xml
 * @link https://pear.php.net/rest/p/mail/maintainers.xml
 *
 * @see \Sweetchuck\PearClient\DataType\MaintainerList
 */
class PackageMaintainersParser extends Base
{

    protected function parseDoIt()
    {
        /** @var \DOMElement $root */
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $this->doc->childNodes);
        $mapping = [
            'c' => 'channel',
            'p' => 'packageName',
        ];
        $this->data += $this->parseTextElements($mapping, $root);
        $this->parseMaintainers();

        return $this;
    }

    protected function parseMaintainers()
    {
        $this->data['list'] = [];
        $elements = $this->xpath->query('/pear:m/pear:m');
        /** @var \DOMElement $element */
        foreach ($elements as $element) {
            $m = $this->parseTextElements($this->maintainerMapping, $element);
            if ($m) {
                if (isset($m['status'])) {
                    settype($m['status'], 'int');
                }

                $this->data['list'][$m['name']] = $m;
            }
        }

        return $this;
    }
}
