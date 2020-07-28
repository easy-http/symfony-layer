<?php

namespace Tests\Mocks;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;

class BaseMock
{
    protected function response($code, $body, $headers = [], $reason = null): PromiseInterface
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        $headers = array_replace(
            ['Content-Type' => 'application/json'],
            $headers
        );

        return new FulfilledPromise(
            new Response($code, $headers, utf8_decode($body), '1.1', utf8_decode($reason))
        );
    }
}
