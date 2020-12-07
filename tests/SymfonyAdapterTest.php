<?php

namespace Tests;

use EasyHttp\LayerContracts\Exceptions\HttpClientException;
use EasyHttp\SymfonyLayer\SymfonyAdapter;
use EasyHttp\SymfonyLayer\SymfonyRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Tests\Concerns\HasHandler;
use Tests\Mocks\PayPalApi;
use Tests\Mocks\RatesApi;
use Tests\Mocks\Responses\PayPalApiResponse;
use Tests\Mocks\Responses\RatesApiResponse;

class SymfonyAdapterTest extends TestCase
{
    use HasHandler;

    /**
     * @test
     */
    public function itCanSendAHttpRequestAndGetTheResponse()
    {
        $handler = $this->createHandler(new RatesApi());
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new SymfonyAdapter($client);

        $response = $adapter->request($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(RatesApiResponse::usd(), $response->parseJson());
    }

    /**
     * @test
     */
    public function itThrowsTheHttpClientExceptionOnServerErrors()
    {
        $this->expectException(HttpClientException::class);
        // TODO: how can I get this message here ?
        // $this->expectExceptionMessage('Error Communicating with Server');

        $handler = $this->createErrorHandler();
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new SymfonyAdapter($client);
        $adapter->request($request);
    }

    /**
     * @test
     */
    public function itCanHandleBasicAuthentication()
    {
        $handler = $this->createHandler(new PayPalApi());
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token', []);
        $user    = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
        $pass    = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';
        $request->setBasicAuth($user, $pass);
        $request->setQuery(['grant_type' => 'client_credentials']);
        $adapter = new SymfonyAdapter($client);

        $response = $adapter->request($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(PayPalApiResponse::token(), $response->parseJson());
    }
}
