<?php

namespace Tests;

use EasyHttp\LayerContracts\Contracts\HttpClientAdapter;
use EasyHttp\LayerContracts\Contracts\HttpClientRequest;
use EasyHttp\SymfonyLayer\SymfonyAdapter;
use EasyHttp\SymfonyLayer\SymfonyRequest;
use Symfony\Component\HttpClient\MockHttpClient;
use Tests\Concerns\HasHandler;

class SymfonyRequestTest extends RequestTest
{
    use HasHandler;

    protected function createRequest(string $method, string $uri): HttpClientRequest
    {
        return new SymfonyRequest($method, $uri, []);
    }

    protected function buildAdapter(): HttpClientAdapter
    {
        $handler = $this->createHandler($this->mock);
        $client = new MockHttpClient($handler);

        return new SymfonyAdapter($client);
    }
}
