<?php

namespace Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use Pleets\HttpClient\Clients\Constants\Client;
use Pleets\HttpClient\Exceptions\HttpClientException;
use Pleets\HttpClient\Exceptions\ResponseNotParsedException;
use Pleets\HttpClient\Standard;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\RatesApiResponse;

class StandardTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSendAHttpRequestAndGetTheResponse()
    {
        $client = new Standard(Client::GUZZLE);
        $client->withHandler(new RatesApi());

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
        $this->expectExceptionMessage('Error Communicating with Server');

        $client = new Standard(Client::GUZZLE);
        $client->withHandler(
            new MockHandler(
                [
                    new RequestException(
                        'Error Communicating with Server',
                        new \GuzzleHttp\Psr7\Request('GET', 'test')
                    ),
                ]
            )
        );

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

        $client = new Standard(Client::GUZZLE);
        $client->withHandler($mock);

        $client->request('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD')->response();
    }
}
