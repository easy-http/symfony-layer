<?php

namespace Tests\Mocks;

use Psr\Http\Message\RequestInterface;
use Tests\Mocks\Concerns\HasBearerAuthentication;
use Tests\Mocks\Concerns\HasFixedResponse;
use Tests\Mocks\Responses\SearchTweetsResponse;

class TwitterApi extends BaseMock
{
    use HasFixedResponse;
    use HasBearerAuthentication;

    protected string $hostname = 'api.twitter.com';

    public function __invoke(RequestInterface $request)
    {
        $response = $this->jsonResponse(400, 'Not found');

        if ($this->response) {
            $response = $this->response;
        } elseif ($request->getUri()->getHost() != $this->hostname) {
            $response = $this->jsonResponse(400, 'Not found');
        } elseif (! $this->isAuthTokenCorrect($request)) {
            $response = $this->jsonResponse(
                401,
                '',
                [
                    'WWW-Authenticate' => 'Bearer realm="example", error="invalid_token", error_description="The access token expired or is not valid"', // phpcs:ignore
                ]
            );
        } elseif ($request->getUri()->getPath() === '/1.1/statuses/user_timeline.json') {
            $response = $this->jsonResponse(200, $this->tweets(), [], 'OK');
        }

        return $response;
    }

    private function tweets(): array
    {
        return SearchTweetsResponse::tweets();
    }
}
