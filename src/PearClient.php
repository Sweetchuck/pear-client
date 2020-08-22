<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient;

use Cache\Adapter\PHPArray\ArrayCachePool;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sweetchuck\PearClient\DataType\Base as DataTypeBase;
use Sweetchuck\PearClient\DataType\CategoryList;
use Sweetchuck\PearClient\DataType\Category;
use Sweetchuck\PearClient\DataType\Maintainer;
use Sweetchuck\PearClient\DataType\MaintainerList;
use Sweetchuck\PearClient\DataType\Package;
use Sweetchuck\PearClient\DataType\PackageList;
use Sweetchuck\PearClient\DataType\PackageMaintainersList;
use Sweetchuck\PearClient\DataType\PackageNameList;
use Sweetchuck\PearClient\DataType\ReleaseList;
use Sweetchuck\PearClient\Middleware\ResponseValidatorMiddleware;
use Sweetchuck\PearClient\Middleware\ResponseXmlToJsonMiddleware;

/**
 * @todo Assert $endpoint input.
 * @todo Assert $endpoint placeholder data types.
 */
class PearClient implements PearClientInterface
{

    public static function getOpenApiFileName(): string
    {
        return dirname(__DIR__) . '/resources/pear.openapi.json';
    }

    public static function createHttpClient(array $options = []): HttpClientInterface
    {
        $options += [
            'base_uri' => 'https://pear.php.net/rest/',
        ];

        if (empty($options['handler'])) {
            $options['handler'] = static::createHttpClientHandlerStack();
        }

        return new HttpClient($options);
    }

    public static function createHttpClientHandlerStack(
        ?string $openApiFileName = null,
        ?LoggerInterface $logger = null,
        ?MessageFormatter $logMessageFormatter = null
    ): HandlerStack {
        $handlerStack = HandlerStack::create();
        static::prepareHttpClientHandlerStack(
            $handlerStack,
            $openApiFileName,
            $logger,
            $logMessageFormatter,
        );

        return $handlerStack;
    }

    public static function prepareHttpClientHandlerStack(
        HandlerStack $handlerStack,
        ?string $openApiFileName = null,
        ?LoggerInterface $logger = null,
        ?MessageFormatter $logMessageFormatter = null
    ) {
        $handlerStack->push(
            static::createResponseValidatorMiddleware($openApiFileName),
            'responseValidator',
        );

        $handlerStack->after(
            'responseValidator',
            static::createResponseXmlToJsonMiddleware(),
            'responseXmlToJson',
        );

        $handlerStack->after(
            'responseXmlToJson',
            static::createResponseLoggerMiddleware($logger, $logMessageFormatter),
            'responseLogger',
        );
    }

    public static function createResponseLoggerMiddleware(
        ?LoggerInterface $logger = null,
        ?MessageFormatter $logMessageFormatter = null
    ): callable {
        if (!$logger) {
            $logger = new NullLogger();
        }

        if (!$logMessageFormatter) {
            $logMessageFormatter = new MessageFormatter();
        }

        return Middleware::log($logger, $logMessageFormatter);
    }

    public static function createResponseXmlToJsonMiddleware(): callable
    {
        return new ResponseXmlToJsonMiddleware();
    }

    public static function createResponseValidatorMiddleware(?string $openApiFileName = null): callable
    {
        return new ResponseValidatorMiddleware(
            static::createResponseValidator($openApiFileName),
        );
    }

    public static function createResponseValidator(?string $openApiFileName = null): ResponseValidator
    {
        return (new ValidatorBuilder)
            ->fromJsonFile($openApiFileName ?: static::getOpenApiFileName())
            ->setCache(new ArrayCachePool())
            ->getResponseValidator();
    }

    protected string $userAgent = 'sweetchuck/pear-client';

    protected HttpClientInterface $client;

    /**
     * @var array|string[]
     *
     * Endpoint and data type pairs.
     */
    protected array $dataTypeMapping = [
        '/c/categories.xml' => CategoryList::class,
        '/c/{categoryName}/info.xml' => Category::class,
        '/c/{categoryName}/packages.xml' => PackageList::class,
        '/c/{categoryName}/packagesinfo.xml' => PackageList::class,
        '/m/allmaintainers.xml' => MaintainerList::class,
        '/m/{maintainerName}/info.xml' => Maintainer::class,
        '/p/packages.xml' => PackageNameList::class,
        '/p/{packageName}/info.xml' => Package::class,
        '/p/{packageName}/maintainers.xml' => PackageMaintainersList::class,
        '/p/{packageName}/maintainers2.xml' => PackageMaintainersList::class,
        '/r/{packageName}/allreleases.xml' => ReleaseList::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        return $this;
    }

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $endpoint, array $options = []): DataTypeBase
    {
        return $this->parseResponseBody(
            $endpoint,
            $this->getSend($endpoint, $options),
        );
    }

    public function getSend(string $endpoint, array $options = []): ResponseInterface
    {
        $path = strtr(
            ltrim($endpoint, '/'),
            $options['endpoint'] ?? [],
        );
        $request = new Request(
            'get',
            new Uri($path),
            $this->getDefaultGetHeaders(),
        );

        return $this->client->send($request, $options);
    }

    public function parseResponseBody(string $endpoint, ResponseInterface $response): DataTypeBase
    {
        $createResponseInstance = [
            $this->dataTypeMapping[$endpoint],
            '__set_state',
        ];
        $body = (string) $response->getBody();

        return $createResponseInstance(json_decode($body, true));
    }

    public function parseResponseString(string $endpoint, ResponseInterface $response): string
    {
        return trim((string) $response->getBody());
    }

    /**
     * {@inheritDoc}
     */
    public function categoriesGet(array $options = []): CategoryList
    {
        return $this->get('/c/categories.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function categoryGet(string $categoryName, array $options = []): Category
    {
        $options['endpoint']['{categoryName}'] = urlencode($categoryName);

        return $this->get('/c/{categoryName}/info.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function categoryPackagesGet(string $categoryName, array $options = []): PackageList
    {
        $options['endpoint']['{categoryName}'] = urlencode($categoryName);

        return $this->get('/c/{categoryName}/packages.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function categoryPackagesInfoGet(string $categoryName, array $options = []): PackageList
    {
        $options['endpoint']['{categoryName}'] = urlencode($categoryName);

        return $this->get('/c/{categoryName}/packagesinfo.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function maintainersGet(array $options = []): MaintainerList
    {
        return $this->get('/m/allmaintainers.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function maintainerGet(string $maintainerName, array $options = []): Maintainer
    {
        $options['endpoint']['{maintainerName}'] = urlencode($maintainerName);

        return $this->get('/m/{maintainerName}/info.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packagesGet(array $options = []): PackageNameList
    {
        return $this->get('/p/packages.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageInfoGet(string $packageName, array $options = []): Package
    {
        $options['endpoint']['{packageName}'] = urlencode(mb_strtolower($packageName));

        return $this->get('/p/{packageName}/info.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageMaintainersGet(string $packageName, array $options = []): PackageMaintainersList
    {
        $options['endpoint']['{packageName}'] = urlencode(mb_strtolower($packageName));

        return $this->get('/p/{packageName}/maintainers.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageMaintainers2Get(string $packageName, array $options = []): PackageMaintainersList
    {
        $options['endpoint']['{packageName}'] = urlencode(mb_strtolower($packageName));

        return $this->get('/p/{packageName}/maintainers2.xml', $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageAllReleasesGet(string $packageName, array $options = []): ReleaseList
    {
        $options['endpoint']['{packageName}'] = urlencode(mb_strtolower($packageName));

        return $this->get('/r/{packageName}/allreleases.xml', $options);
    }

    public function packageStabilityGet(string $stability, string $packageName, array $options = []): ?string
    {
        $endpoint = "r/{packageName}/$stability.txt";
        $options['endpoint']['{packageName}'] = urlencode(mb_strtolower($packageName));

        $result = null;
        try {
            $result = $this->parseResponseString(
                $endpoint,
                $this->getSend($endpoint, $options),
            );
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() !== 404) {
                throw $e;
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function packageLatestGet(string $packageName, array $options = []): ?string
    {
        return $this->packageStabilityGet('latest', $packageName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageStableGet(string $packageName, array $options = []): ?string
    {
        return $this->packageStabilityGet('stable', $packageName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageBetaGet(string $packageName, array $options = []): ?string
    {
        return $this->packageStabilityGet('beta', $packageName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageAlphaGet(string $packageName, array $options = []): ?string
    {
        return $this->packageStabilityGet('alpha', $packageName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function packageDevelGet(string $packageName, array $options = []): ?string
    {
        return $this->packageStabilityGet('devel', $packageName, $options);
    }

    protected function getDefaultGetHeaders(): array
    {
        return [
            'User-Agent' => $this->userAgent,
            'Accept' => 'application/xml',
        ];
    }
}
