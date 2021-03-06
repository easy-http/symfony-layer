<?php

namespace Tests\Concerns;

use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Response\MockResponse;

trait HasHandler
{
    private function createHandler($mock): callable
    {
        return function ($method, $url, $options) use ($mock) {
            $headers = [];
            foreach ($options['headers'] as $header) {
                preg_match('#([a-zA-Z]+-?[a-zA-Z]+):\s+(.*)#', $header, $matches);
                $headers[$matches[1]] = $matches[2];
            }

            /**
             * @var \GuzzleHttp\Promise\FulfilledPromise $response
             */
            $response = $mock(new Request($method, $url, $headers, $options['body'] ?? null), $options);

            /**
             * @var \GuzzleHttp\Psr7\Response $res
             */
            $response = $response->wait();

            return new MockResponse(
                $response->getBody()->getContents(),
                [
                    'url'              => $url,
                    'http_code'        => $response->getStatusCode(),
                    'response_headers' => $response->getHeaders(),
                ]
            );
        };
    }

    private function createErrorHandler(): callable
    {
        return function () {
            $response = new MockResponse(
                'Error Communicating with Server',
                [
                    'http_code' => 500,
                    'url'       => 'test',
                ]
            );
            throw new ServerException($response);
        };
    }
}
