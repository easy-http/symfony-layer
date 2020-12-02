<?php

namespace Pleets\HttpClient;

use Pleets\HttpClient\Contracts\HttpClientRequest;

class SymfonyRequest implements HttpClientRequest
{
    protected string $method;

    protected string $uri;

    protected array $headers = [];

    protected array $json = [];

    protected array $query = [];

    protected int $timeout = 10;

    protected bool $ssl = false;

    protected array $options;

    protected array $basicAuth = [];

    public function __construct(string $method, string $uri, array $options = [])
    {
        $this->method  = $method;
        $this->uri     = $uri;
        $this->options = $options;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getJson(): array
    {
        return $this->json;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function setJson(array $json): self
    {
        $this->json = $json;

        return $this;
    }

    public function setQuery(array $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setBasicAuth(string $username, string $password): self
    {
        $this->basicAuth = [$username, $password];

        return $this;
    }

    public function ssl(bool $ssl): void
    {
        $this->ssl = $ssl;
    }

    public function options()
    {
        $options = [
            'timeout' => $this->timeout,
            'verify_peer' => $this->ssl,
            'headers' => $this->headers,
        ];

        if ($this->json) {
            $options['headers'] = array_merge(['Content-Type' => 'application/json;charset=UTF-8'], $this->headers);
            $options['json']    = $this->json;
        }

        if ($this->query) {
            $options['query'] = $this->query;
        }

        if ($this->basicAuth) {
            $options['auth_basic'] = $this->basicAuth;
        }

        return $options;
    }
}
