<?php

namespace EasyHttp\SymfonyLayer;

use EasyHttp\SymfonyLayer\Factories\ClientFactory;
use EasyHttp\SymfonyLayer\Contracts\HttpClientAdapter;
use EasyHttp\SymfonyLayer\Contracts\HttpClientRequest;
use EasyHttp\SymfonyLayer\Contracts\HttpClientResponse;

class SymfonyClient
{
    protected string $client;

    protected HttpClientAdapter $adapter;

    protected HttpClientRequest $request;

    protected $handler;

    public function request(string $method, string $uri): HttpClientResponse
    {
        $request = new SymfonyRequest($method, $uri);
        return $this->adapter()->request($request);
    }

    public function withHandler(callable $handler)
    {
        unset($this->adapter);
        $this->handler = $handler;
    }

    public function prepareRequest(string $method, string $uri): self
    {
        $this->request = new SymfonyRequest($method, $uri);

        return $this;
    }

    public function execute(): HttpClientResponse
    {
        return $this->adapter()->request($this->request);
    }

    public function setHeader(string $key, string $value): self
    {
        $this->request->setHeader($key, $value);

        return $this;
    }

    public function setJson(array $json): self
    {
        $this->request->setJson($json);

        return $this;
    }

    public function setQuery(array $query): self
    {
        $this->request->setQuery($query);

        return $this;
    }

    public function ssl(bool $ssl): self
    {
        $this->request->ssl($ssl);

        return $this;
    }

    public function setBasicAuth(string $username, string $password): self
    {
        $this->request->setBasicAuth($username, $password);

        return $this;
    }

    private function adapter(): HttpClientAdapter
    {
        if ($this->hasAdapter()) {
            return $this->adapter;
        }

        $this->adapter = new SymfonyAdapter(ClientFactory::build($this->handler));

        return $this->adapter;
    }

    private function hasAdapter(): bool
    {
        return (bool) ($this->adapter ?? null);
    }
}
