<?php

namespace Pleets\HttpClient\Contracts;

interface HttpClientAdapter
{
    public function request(HttpClientRequest $request): HttpClientResponse;
}
