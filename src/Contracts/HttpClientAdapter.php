<?php

namespace EasyHttp\SymfonyLayer\Contracts;

interface HttpClientAdapter
{
    public function request(HttpClientRequest $request): HttpClientResponse;
}
