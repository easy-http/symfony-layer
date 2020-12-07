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
        $response = $this->jsonResponse(400, 'Not found');

        if ($this->response) {
            $response = $this->response;
        } elseif ($request->getUri()->getHost() != $this->hostname) {
            $response = $this->jsonResponse(400, 'Not found');
        } elseif (preg_match('#^\/api\/\d{4}-\d{2}-\d{2}\/#', $request->getUri()->getPath())) {
            $response = $this->jsonResponse(200, $this->usd(), [], 'OK');
        }

        return $response;
    }

    private function usd(): array
    {
        return RatesApiResponse::usd();
    }
}
