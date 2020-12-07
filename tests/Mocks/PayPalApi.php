<?php

namespace Tests\Mocks;

use Psr\Http\Message\RequestInterface;
use Tests\Mocks\Concerns\HasBasicAuthentication;
use Tests\Mocks\Concerns\HasFixedResponse;
use Tests\Mocks\Responses\PayPalApiResponse;

class PayPalApi extends BaseMock
{
    use HasFixedResponse;
    use HasBasicAuthentication;

    protected $hostname = 'api.sandbox.paypal.com';

    protected $user = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
    protected $pass = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';

    public function __invoke(RequestInterface $request)
    {
        if ($this->response) {
            return $this->response;
        }

        if ($request->getUri()->getHost() != $this->hostname) {
            return $this->jsonResponse(400, 'Not found');
        }

        $response = $this->jsonResponse(400, 'Not found');

        if ($request->getUri()->getPath() === '/v1/oauth2/token') {
            if ($request->getMethod() === 'GET') {
                $response = $this->jsonResponse(401, $this->invalidToken(), [], 'OK');
            } elseif (! $this->isAuthTokenCorrect($request)) {
                $response = $this->jsonResponse(401, $this->failureAuthentication(), [], 'OK');
            } elseif (empty($request->getUri()->getQuery())) {
                $response = $this->jsonResponse(401, $this->unsupportedGrantType(), [], 'OK');
            } else {
                $response = $this->jsonResponse(200, $this->token(), [], 'OK');
            }
        }

        return $response;
    }

    private function token(): array
    {
        return PayPalApiResponse::token();
    }

    private function invalidToken(): array
    {
        return PayPalApiResponse::invalidToken();
    }

    private function failureAuthentication(): array
    {
        return PayPalApiResponse::failureAuthentication();
    }

    private function unsupportedGrantType(): array
    {
        return PayPalApiResponse::missingGrantType();
    }
}
