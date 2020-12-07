<?php

namespace EasyHttp\SymfonyLayer;

use EasyHttp\LayerContracts\Contracts\HttpClientResponse;
use EasyHttp\LayerContracts\Exceptions\ImpossibleToParseJsonException;
use EasyHttp\SymfonyLayer\Concerns\NeedsParseHeaders;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyResponse implements HttpClientResponse
{
    use NeedsParseHeaders;

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
        return $this->parseHeaders($this->response->getHeaders(false));
    }

    public function getBody(): string
    {
        return $this->toString();
    }

    public function parseJson(): array
    {
        $response = $this->toString();
        $data     = json_decode($response, true);

        if (! $data) {
            throw new ImpossibleToParseJsonException(
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
