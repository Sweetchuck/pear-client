<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Middleware;

use DOMDocument;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sweetchuck\PearClient\Parser\AllMaintainersParser;
use Sweetchuck\PearClient\Parser\AllPackagesParser;
use Sweetchuck\PearClient\Parser\AllReleasesParser;
use Sweetchuck\PearClient\Parser\CategoriesParser;
use Sweetchuck\PearClient\Parser\CategoryPackageInfoParser;
use Sweetchuck\PearClient\Parser\CategoryPackagesParser;
use Sweetchuck\PearClient\Parser\CategoryParser;
use Sweetchuck\PearClient\Parser\MaintainerParser;
use Sweetchuck\PearClient\Parser\PackageMaintainersParser;
use Sweetchuck\PearClient\Parser\PackageParser;
use Sweetchuck\PearClient\ParserInterface;
use Sweetchuck\PearClient\Utils;

class ResponseXmlToJsonMiddleware
{

    /**
     * @var string[]
     */
    public array $parserMapping = [
        'http://pear.php.net/dtd/rest.allcategories.xsd' => CategoriesParser::class,
        'http://pear.php.net/dtd/rest.category.xsd' => CategoryParser::class,
        'http://pear.php.net/dtd/rest.categorypackages.xsd' => CategoryPackagesParser::class,
        'http://pear.php.net/dtd/rest.categorypackageinfo.xsd' => CategoryPackageInfoParser::class,
        'http://pear.php.net/dtd/rest.allmaintainers.xsd' => AllMaintainersParser::class,
        'http://pear.php.net/dtd/rest.maintainer.xsd' => MaintainerParser::class,
        'http://pear.php.net/dtd/rest.allpackages.xsd' => AllPackagesParser::class,
        'http://pear.php.net/dtd/rest.package.xsd' => PackageParser::class,
        'http://pear.php.net/dtd/rest.packagemaintainers.xsd' => PackageMaintainersParser::class,
        'http://pear.php.net/dtd/rest.packagemaintainers2.xsd' => PackageMaintainersParser::class,
        'http://pear.php.net/dtd/rest.allreleases.xsd' => AllReleasesParser::class,
    ];

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            /** @var \GuzzleHttp\Promise\PromiseInterface $promise */
            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) use ($request, $options) {
                    if ($response->getHeaderLine('Content-Type') !== 'application/xml') {
                        return $response;
                    }

                    $xml = $response->getBody()->getContents();
                    $doc = new DOMDocument();
                    // @todo Error handling.
                    $isXml = $doc->loadXML($xml);
                    if (!$isXml) {
                        return $response;
                    }

                    $parser = $this->getParser($doc);
                    if (!$parser) {
                        return $response;
                    }

                    $stream = new Stream(Utils::stringToResource(json_encode($parser->parse($doc))));

                    return $response
                        ->withHeader('Content-Type', 'application/json')
                        ->withBody($stream);
                },
            );
        };
    }

    /**
     * @todo Instantiate parsers from a container.
     */
    protected function getParser(DOMDocument $doc): ?ParserInterface
    {
        $root = Utils::domFirstOfType(XML_ELEMENT_NODE, $doc->childNodes);
        if (!$root || !$root->hasAttribute('xsi:schemaLocation')) {
            return null;
        }

        $schemaLocations = preg_split(
            '/\s+/u',
            trim($root->getAttribute('xsi:schemaLocation')),
        );
        foreach ($schemaLocations as $schemaLocation) {
            if (!array_key_exists($schemaLocation, $this->parserMapping)) {
                continue;
            }

            $class = $this->parserMapping[$schemaLocation];

            return new $class();
        }

        return null;
    }
}
