<?php

namespace Sweetchuck\PearClient\Test;

use Codeception\Actor;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JsonSchema\Validator;
use Sweetchuck\PearClient\Middleware\ResponseXmlToJsonMiddleware;
use Sweetchuck\PearClient\PearClient;
use Sweetchuck\PearClient\PearClientInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends Actor
{
    use _generated\UnitTesterActions;

    public string $baseUri = 'https://127.0.0.1/rest/';

    public PearClientInterface $client;

    public function &initClient(array $responses, array $options): array
    {
        $defaultResponse = [
            'statusCode' => 200,
            'headers' => [
                'Content-Type' => 'application/xml',
            ],
            'body' => '',
        ];

        $mock = new MockHandler();
        foreach ($responses as $response) {
            $response += $defaultResponse;
            $mock->append(new Response(
                $response['statusCode'],
                $response['headers'],
                $response['body']
            ));
        }
        $mock->append(new RequestException(
            'Error Communicating with Server',
            new Request('GET', 'too-much-request')
        ));

        $container = [];
        $history = Middleware::history($container);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history, 'history');
        PearClient::prepareHttpClientHandlerStack($handlerStack);

        $this->client = new PearClient(
            PearClient::createHttpClient([
                'base_uri' => 'https://127.0.0.1/rest/',
                'handler' => $handlerStack,
            ]),
        );

        $this->client->setOptions($options);

        return $container;
    }

    public function assertHttpRequest(array $expected, Request $actual, ?string $message = null)
    {
        if ($message === null) {
            $message = 'HTTP request';
        }

        if (array_key_exists('method', $expected)) {
            $this->assertSame(
                $expected['method'],
                $actual->getMethod(),
                "$message - method"
            );
        }

        if (array_key_exists('uri', $expected)) {
            $this->assertSame(
                $expected['uri'],
                $actual->getUri()->__toString(),
                "$message - uri"
            );
        }

        if (array_key_exists('headers', $expected)) {
            $this->assertSame(
                $expected['headers'],
                $actual->getHeaders(),
                "$message - headers"
            );
        }

        if (array_key_exists('body', $expected)) {
            $this->assertSame(
                $expected['body'],
                $actual->getBody()->getContents(),
                "$message - body"
            );
        }
    }

    public function assertJsonSchema(array $schema, $actual, string $message = '')
    {
        $validator = new Validator();

        $a = json_decode(json_encode($actual), false);
        $validator->validate($a, $schema);
        $errors = $validator->getErrors();

        if ($errors) {
            if (!$message) {
                $message = 'JSON schema';
            }

            $errorMessage = $this->jsonSchemaValidationErrorsToMessage($errors);
            $this->fail("$message:\n$errorMessage");
        }
    }

    public function getDefaultGetRequestHeaders(): array
    {
        return [
            'Host' => [parse_url($this->baseUri, PHP_URL_HOST)],
            'User-Agent' => ['sweetchuck/pear-client'],
            'Accept' => ['application/xml'],
        ];
    }

    public function getDefaultPostRequestHeaders(): array
    {
        return [
            'Host' => [parse_url($this->baseUri, PHP_URL_HOST)],
            'User-Agent' => ['sweetchuck/pear-client'],
        ];
    }

    public function jsonSchemaValidationErrorsToMessage(array $errors): string
    {
        $msg = [];
        foreach ($errors as $error) {
            $msg[] = $this->jsonSchemaValidationErrorToMessage($error);
        }

        return implode(PHP_EOL, $msg);
    }

    public function jsonSchemaValidationErrorToMessage(array $error): string
    {
        $msg = [];
        foreach ($error as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $msg[] = "$key: $value";
        }

        return implode('; ', $msg);
    }
}
