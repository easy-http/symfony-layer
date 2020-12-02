<?php

namespace Tests;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use EasyHttp\SymfonyLayer\Exceptions\HttpClientException;
use EasyHttp\SymfonyLayer\Exceptions\ResponseNotParsedException;
use EasyHttp\SymfonyLayer\SymfonyClient;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\RatesApiResponse;

class SymfonyClientTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSendAHttpRequestAndGetTheResponse()
    {
        $client = new SymfonyClient();
        $client->withHandler($this->createHandler($mock = new RatesApi()));

        $response = $client->request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(RatesApiResponse::usd(), $response->response());
    }

    /**
     * @test
     */
    public function itThrowsTheHttpClientExceptionOnServerErrors()
    {
        $this->expectException(HttpClientException::class);

        $client = new SymfonyClient();
        $client->withHandler($this->createErrorHandler());

        $client->request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD');
    }

    /**
     * @test
     */
    public function itThrowsTheNotParsedExceptionOnInvalidJsonString()
    {
        $this->expectException(ResponseNotParsedException::class);

        $mock = new RatesApi();
        $mock->withResponse(200, 'some string');

        $client = new SymfonyClient();
        $client->withHandler($this->createHandler($mock));

        $client->request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD')->response();
    }

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
