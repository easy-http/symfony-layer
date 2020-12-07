<?php

namespace Tests;

use EasyHttp\LayerContracts\Exceptions\ImpossibleToParseJsonException;
use EasyHttp\SymfonyLayer\SymfonyAdapter;
use EasyHttp\SymfonyLayer\SymfonyClient;
use EasyHttp\SymfonyLayer\SymfonyRequest;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Tests\Concerns\HasHandler;
use Tests\Mocks\RatesApi;

class SymfonyResponseTest extends TestCase
{
    use HasHandler;

    /**
     * @TODO: body is empty
     */
    public function itGetsTheBody()
    {
        $handler = $this->createHandler(new RatesApi());
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://http-info-api.com/some-end-point', []);
        $adapter = new SymfonyAdapter($client);

        $response = $adapter->request($request);

        $this->assertNotEmpty($response->getBody());
        $this->assertSame('Not found', $response->getBody());
    }

    /**
     * @test
     */
    public function itGetsHeaders()
    {
        $handler = $this->createHandler(new RatesApi());
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://http-info-api.com/some-end-point', []);
        $adapter = new SymfonyAdapter($client);

        $response = $adapter->request($request);

        // TODO: why needs to be parsed to lower ?
        $this->assertSame(
            [
                strtolower('Server') => 'Apache/2.4.41 (Ubuntu)',
                strtolower('Cache-Control') => 'no-cache, private',
                strtolower('Content-Type') => 'application/json'
            ],
            $response->getHeaders()
        );
    }

    /**
     * @test
     */
    public function itThrowsTheNotParsedExceptionOnInvalidJsonString()
    {
        $this->expectException(ImpossibleToParseJsonException::class);

        $mock = new RatesApi();
        $mock->withResponse(200, 'some string');
        $handler = $this->createHandler($mock);
        $client = new MockHttpClient($handler);

        $request = new SymfonyRequest('POST', 'https://api.ratesapi.io/api/2020-07-24/?base=USD', []);
        $adapter = new SymfonyAdapter($client);

        $adapter->request($request)->parseJson();
    }
}
