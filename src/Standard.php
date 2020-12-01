<?php

namespace Pleets\HttpClient;

use Pleets\HttpClient\Clients\AdapterFactory;
use Pleets\HttpClient\Clients\Constants\Client;
use Pleets\HttpClient\Clients\RequestFactory;
use Pleets\HttpClient\Contracts\HttpClientAdapter;
use Pleets\HttpClient\Contracts\HttpClientRequest;
use Pleets\HttpClient\Contracts\HttpClientResponse;

class Standard
{
    protected string $client;

    protected HttpClientAdapter $adapter;

    protected HttpClientRequest $request;

    protected $handler;

    public function __construct(string $client = Client::GUZZLE)
    {
        $this->client = $client;
    }

    public function request(string $method, string $uri): HttpClientResponse
    {
        $request = RequestFactory::build($this->client, $method, $uri);
        return $this->adapter()->request($request);
    }

    public function withHandler($handler)
    {
        unset($this->adapter);
        $this->handler = $handler;
    }

    public function prepareRequest(string $method, string $uri): self
    {
        $this->request = RequestFactory::build($this->client, $method, $uri);

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
        if ($this->adapterShouldBeCreatedWithHandler()) {
            $this->adapter = AdapterFactory::build($this->client);
        } elseif ($this->adapterShouldBeCreatedWithoutHandler()) {
            $this->adapter = AdapterFactory::build($this->client, $this->handler);
        }

        return $this->adapter;
    }

    private function adapterShouldBeCreatedWithHandler(): bool
    {
        return ! $this->hasAdapter() && ! $this->hasHandler();
    }

    private function adapterShouldBeCreatedWithoutHandler(): bool
    {
        return ! $this->hasAdapter() && $this->hasHandler();
    }

    private function hasAdapter(): bool
    {
        return (bool) ($this->adapter ?? null);
    }

    private function hasHandler(): bool
    {
        return (bool) ($this->handler ?? null);
    }
}
