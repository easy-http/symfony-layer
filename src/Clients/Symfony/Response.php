<?php

namespace Pleets\HttpClient\Clients\Symfony;

use Pleets\HttpClient\Contracts\HttpClientResponse;
use Pleets\HttpClient\Exceptions\ResponseNotParsedException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Response implements HttpClientResponse
{
    protected ResponseInterface $response;

    private string $contents;

    public function __construct(ResponseInterface $response, string $contents = '')
    {
        $this->response = $response;
        $this->contents = $contents;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders(false);
    }

    public function response(): array
    {
        $response = $this->toString();
        $data     = json_decode($response, true);

        if (! $data) {
            throw new ResponseNotParsedException(
                'Service response could not be parsed to JSON, Response: ' .
                $response . ', Reason: ' . json_last_error()
            );
        }

        return $data;
    }

    private function toString(): string
    {
        return $this->contents;
    }
}
