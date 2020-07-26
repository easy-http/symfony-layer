<?php

namespace Tests\Mocks;

use Psr\Http\Message\RequestInterface;
use Tests\Mocks\Concerns\HasFixedResponse;
use Tests\Mocks\Responses\RatesApiResponse;

class RatesApi extends BaseMock
{
    use HasFixedResponse;

    protected $hostname = 'api.ratesapi.io';

    public function __invoke(RequestInterface $request)
    {
        if ($this->response) {
            return $this->response;
        }

        if ($request->getUri()->getHost() != $this->hostname) {
            return $this->response(400, 'Not found');
        }

        if (preg_match('#^\/api\/\d{4}-\d{2}-\d{2}\/#', $request->getUri()->getPath())) {
            return $this->response(200, $this->usd(), [], 'OK');
        }

        return $this->response(400, 'Not found');
    }

    private function usd(): array
    {
        return RatesApiResponse::usd();
    }
}
