<?php

namespace Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use Pleets\HttpClient\Clients\Constants\Client;
use Pleets\HttpClient\Exceptions\HttpClientException;
use Pleets\HttpClient\Exceptions\ResponseNotParsedException;
use Pleets\HttpClient\Standard;
use Tests\Mocks\PayPalApi;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\PayPalApiResponse;
use Tests\Mocks\Responses\RatesApiResponse;
use Tests\Mocks\Responses\SearchTweetsResponse;
use Tests\Mocks\TwitterApi;

class GuzzleStandardTest extends TestCase
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

    /**
     * @test
     */
    public function itCanSetHeadersOnRequests()
    {
        $client = new Standard(Client::GUZZLE);
        $client->withHandler(new TwitterApi());

        $client->prepareRequest(
            'GET',
            'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=darioriverat&count=7'
        );
        $token = 'tGzv3JOkF0XG5Qx2TlKWIA';
        $client->setHeader('Authorization', 'Bearer ' . $token);
        $response = $client->execute();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(SearchTweetsResponse::tweets(), $response->response());
    }

    /**
     * @test
     */
    public function itCanHandleBasicAuthentication()
    {
        $client = new Standard(Client::GUZZLE);
        $client->withHandler(new PayPalApi());

        $client->prepareRequest('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token');
        $user = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
        $pass = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';
        $client->setBasicAuth($user, $pass);
        $client->setQuery(['grant_type' => 'client_credentials']);
        $response = $client->execute();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(PayPalApiResponse::token(), $response->response());
    }
}
