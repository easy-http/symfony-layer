<?php

namespace Pleets\HttpClient\Clients\Guzzle;

use Pleets\HttpClient\Contracts\HttpClientRequest;
use Pleets\HttpClient\Contracts\value;

class Request implements HttpClientRequest
{
    protected string $method;

    protected string $uri;

    protected array $headers = [];

    protected array $json = [];

    protected int $timeout = 10;

    protected bool $ssl = false;

    protected array $options;

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

    public function ssl(bool $ssl): void
    {
        $this->ssl = $ssl;
    }

    public function options()
    {
        $options = [
            'headers' => array_merge(['Content-Type' => 'application/json;charset=UTF-8'], $this->headers),
            'timeout' => $this->timeout,
            'verify' => $this->ssl,
        ];

        if ($this->json) {
            $options['json'] = $this->json;
        }

        return $options;
    }
}
