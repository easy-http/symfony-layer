<?php

namespace Pleets\HttpClient\Contracts;

interface HttpClientRequest
{
    public function getMethod(): string;
    public function getUri(): string;
    public function getJson(): array;
    public function setMethod(string $method): self;
    public function setUri(string $uri): self;
    public function setJson(array $json): self;
    public function ssl(bool $ssl): void;
    public function options();
}
