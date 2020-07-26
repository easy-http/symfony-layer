<?php

namespace Pleets\HttpClient\Clients\Guzzle;

use Pleets\HttpClient\Contracts\HttpClientRequest;

class Request implements HttpClientRequest
{
    protected string $method;

    protected string $uri;

    protected array $json = [];

    protected int $timeout = 10;

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

    public function setJson(array $json): self
    {
        $this->json = $json;

        return $this;
    }

    public function options()
    {
        return [
            'headers' => ['Content-Type' => 'application/json;charset=UTF-8'],
            'timeout' => $this->timeout,
            'json'    => $this->json,
        ];
    }
}
