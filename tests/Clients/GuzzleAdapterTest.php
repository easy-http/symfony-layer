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
use Tests\Mocks\PayPalApi;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\PayPalApiResponse;
use Tests\Mocks\Responses\RatesApiResponse;
use Tests\Mocks\Responses\SearchTweetsResponse;
use Tests\Mocks\TwitterApi;

class GuzzleAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSendAHttpRequestAndGetTheResponse()
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
    public function itThrowsTheHttpClientExceptionOnServerErrors()
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

    /**
     * @test
     */
    public function itCanSetHeadersOnRequests()
    {
        $handler = HandlerStack::create(new TwitterApi());
        $client  = new Client(['handler' => $handler]);

        $request = new Request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=darioriverat&count=7', []);
        $token   = 'tGzv3JOkF0XG5Qx2TlKWIA';
        $request->setHeader('Authorization', 'Bearer ' . $token);
        $adapter = new Adapter($client);

        $response = $adapter->request($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(SearchTweetsResponse::tweets(), $response->response());
    }

    /**
     * @test
     */
    public function itCanHandleBasicAuthentication()
    {
        $handler = HandlerStack::create(new PayPalApi());
        $client  = new Client(['handler' => $handler]);

        $request = new Request('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token', []);
        $user    = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
        $pass    = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';
        $request->setBasicAuth($user, $pass);
        $request->setQuery(['grant_type' => 'client_credentials']);
        $adapter = new Adapter($client);

        $response = $adapter->request($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(PayPalApiResponse::token(), $response->response());
    }
}
