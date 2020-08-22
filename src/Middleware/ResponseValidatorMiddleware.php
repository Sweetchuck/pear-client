<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Middleware;

use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sweetchuck\PearClient\EndpointAwareInterface;
use Sweetchuck\PearClient\Utils;

class ResponseValidatorMiddleware
{

    protected ResponseValidator $responseValidator;

    public function __construct(ResponseValidator $responseValidator)
    {
        $this->responseValidator = $responseValidator;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            /** @var \GuzzleHttp\Promise\PromiseInterface $promise */
            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) use ($request, $options) {
                    $endpointMeta = Utils::endpointFromUri((string) $request->getUri());
                    if (!$endpointMeta) {
                        return $response;
                    }

                    $this->responseValidator->validate(
                        new OperationAddress(
                            $endpointMeta['endpoint'],
                            mb_strtolower($request->getMethod()),
                        ),
                        $response,
                    );

                    return $response;
                },
            );
        };
    }
}
