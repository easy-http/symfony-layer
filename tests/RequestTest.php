<?php

namespace Tests;

use EasyHttp\LayerContracts\Contracts\HttpClientAdapter;
use EasyHttp\LayerContracts\Contracts\HttpClientRequest;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\HasMock;
use Tests\Mocks\HttpInfo;

abstract class RequestTest extends TestCase
{
    use HasMock;

    protected string $uri = 'https://example.com/api';

    abstract protected function createRequest(string $method, string $uri): HttpClientRequest;
    abstract protected function buildAdapter(): HttpClientAdapter;

    /**
     * @test
     */
    public function itSetsMethodAndUri()
    {
        $this->setMock(new HttpInfo());

        $request = $this->createRequest('GET', $this->uri);
        $response = $this->buildAdapter()->request($request)->parseJson();

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('https://example.com/api', $request->getUri());
        $this->assertSame($request->getMethod(), $response['method']);
        $this->assertSame($request->getUri(), $response['uri']);

        $request->setMethod('POST');
        $request->setUri('http://other-uri.com');

        $response = $this->buildAdapter()->request($request)->parseJson();

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://other-uri.com', $request->getUri());
        $this->assertSame($request->getMethod(), $response['method']);
        $this->assertSame($request->getUri() . '/', $response['uri']);
    }

    /**
     * @test
     */
    public function itSetsJson()
    {
        $this->setMock(new HttpInfo());

        $request = $this->createRequest('GET', $this->uri);
        $request->setJson(['key' => 'value']);
        $response = $this->buildAdapter()->request($request)->parseJson();

        $this->assertSame(['key' => 'value'], $request->getJson());
        $this->assertSame('{"key":"value"}', $response['body']);
    }

    /**
     * @test
     */
    public function itSetsQuery()
    {
        $this->setMock(new HttpInfo());

        $request = $this->createRequest('GET', $this->uri);
        $request->setQuery(['key' => 'value']);
        $response = $this->buildAdapter()->request($request)->parseJson();

        $this->assertSame(['key' => 'value'], $request->getQuery());
        $this->assertSame('key=value', $response['uriFragment']['query']);
    }

    /**
     * @test
     */
    public function itSetsHeaders()
    {
        $this->setMock(new HttpInfo());

        $request = $this->createRequest('GET', $this->uri);
        $request->setHeader('key', 'value');
        $response = $this->buildAdapter()->request($request)->parseJson();

        $this->assertSame('value', $request->getHeader('key'));
        $this->assertArrayHasKey('key', $response['headers']);
        $this->assertSame('value', $response['headers']['key']);
    }
}
