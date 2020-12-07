<?php

namespace EasyHttp\SymfonyLayer;

use EasyHttp\LayerContracts\AbstractClient;
use EasyHttp\LayerContracts\Contracts\HttpClientAdapter;
use EasyHttp\LayerContracts\Contracts\HttpClientRequest;
use EasyHttp\SymfonyLayer\Factories\ClientFactory;

class SymfonyClient extends AbstractClient
{
    protected function buildRequest(string $method, string $uri): HttpClientRequest
    {
        return new SymfonyRequest($method, $uri);
    }

    protected function buildAdapter(): HttpClientAdapter
    {
        return new SymfonyAdapter(ClientFactory::build($this->handler));
    }
}
