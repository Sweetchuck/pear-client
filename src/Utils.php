<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient;

use DOMNode;

class Utils
{

    /**
     * @param int $type
     *   One of the \XML_*_NODE constant.
     * @param iterable|DOMNode[] $nodes
     *
     * @see \XML_ELEMENT_NODE
     */
    public static function domFirstOfType(int $type, iterable $nodes): ?DOMNode
    {
        foreach ($nodes as $node) {
            if ($node->nodeType === $type) {
                return $node;
            }
        }

        return null;
    }

    /**
     * @return resource
     */
    public static function stringToResource(string $string)
    {
        return fopen(
            "data://text/plain;base64," . base64_encode($string),
            'rw',
        );
    }

    public static function endpointFromUri(string $path): array
    {
        $patterns = [
            '@/c/categories\.xml$@' => '/c/categories.xml',
            '@/c/(?P<categoryName>[^/]+)/info\.xml$@' => '/c/{categoryName}/info.xml',
            '@/c/(?P<categoryName>[^/]+)/packages\.xml$@' => '/c/{categoryName}/packages.xml',
            '@/c/(?P<categoryName>[^/]+)/packagesinfo\.xml$@' => '/c/{categoryName}/packagesinfo.xml',
            '@/m/allmaintainers\.xml$@' => '/m/allmaintainers.xml',
            '@/m/(?P<maintainerName>[^/]+)/info\.xml$@' => '/m/{maintainerName}/info.xml',
            '@/p/packages\.xml$@' => '/p/packages.xml',
            '@/p/(?P<packageName>[^/]+)/info\.xml$@' => '/p/{packageName}/info.xml',
            '@/p/(?P<packageName>[^/]+)/maintainers\.xml$@' => '/p/{packageName}/maintainers.xml',
            '@/p/(?P<packageName>[^/]+)/maintainers2\.xml$@' => '/p/{packageName}/maintainers2.xml',
            '@/r/(?P<packageName>[^/]+)/allreleases\.xml$@' => '/r/{packageName}/allreleases.xml',
        ];

        foreach ($patterns as $pattern => $endpoint) {
            if (preg_match($pattern, $path, $matches)) {
                return [
                    'endpoint' => $endpoint,
                    'parameters' => array_filter(
                        $matches,
                        function ($key) {
                            return !is_numeric($key);
                        },
                        \ARRAY_FILTER_USE_KEY,
                    ),
                ];
            }
        }

        return [];
    }
}
