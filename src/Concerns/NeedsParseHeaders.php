<?php

namespace EasyHttp\SymfonyLayer\Concerns;

trait NeedsParseHeaders
{
    protected function parseHeaders(array $headers): array
    {
        $_headers = [];

        foreach ($headers as $key => $value) {
            $_headers[$key] = array_shift($value);
        }

        return $_headers;
    }
}
