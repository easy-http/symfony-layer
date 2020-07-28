<?php

namespace Tests\Mocks\Concerns;

use GuzzleHttp\Promise\PromiseInterface;

trait HasFixedResponse
{
    protected ?PromiseInterface $response = null;

    public function withResponse(int $code, string $body): void
    {
        $this->response = $this->response($code, $body);
    }
}
