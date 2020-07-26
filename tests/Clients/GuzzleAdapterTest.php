<?php

namespace Tests\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Pleets\HttpClient\Clients\Guzzle\Adapter;
use Pleets\HttpClient\Clients\Guzzle\Request;
use Pleets\HttpClient\Exceptions\HttpClientException;
use Pleets\HttpClient\Exceptions\ResponseNotParsedException;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\RatesApiResponse;

class GuzzleAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSendSomeHttpRequestAndGetTheResponse()
    {
        $handler = HandlerStack::create(new RatesApi());
        $client  = new Client(['handler' => $handler]);

        $request = new Request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new Adapter($client);

        $response = $adapter->request($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(RatesApiResponse::usd(), $response->response());
    }

    /**
     * @test
     */
    public function itThrowsSomeHttpClientExceptionOnServerErrors()
    {
        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Error Communicating with Server');

        $handler = new MockHandler(
            [
                new RequestException('Error Communicating with Server', new \GuzzleHttp\Psr7\Request('GET', 'test')),
            ]
        );
        $client  = new Client(['handler' => $handler]);

        $request = new Request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new Adapter($client);
        $adapter->request($request);
    }

    /**
     * @test
     */
    public function itThrowsTheNotParsedExceptionOnInvalidJsonString()
    {
        $this->expectException(ResponseNotParsedException::class);

        $mock = new RatesApi();
        $mock->withResponse(200, 'some string');
        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler' => $handler]);

        $request = new Request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new Adapter($client);

        $adapter->request($request)->response();
    }
}
