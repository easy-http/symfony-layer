<?php

namespace Pleets\HttpClient\Contracts;

interface HttpClientRequest
{
    public function getMethod(): string;
    public function getUri(): string;
    public function options();
}
