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

    protected $hostname = 'api.twitter.com';

    public function __invoke(RequestInterface $request)
    {
        if ($this->response) {
            return $this->response;
        }

        if ($request->getUri()->getHost() != $this->hostname) {
            return $this->response(400, 'Not found');
        }

        if (! $this->isAuthTokenCorrect($request)) {
            return $this->response(
                401,
                '',
                [
                    'WWW-Authenticate' => 'Bearer realm="example", error="invalid_token", error_description="The access token expired or is not valid"', // phpcs:ignore
                ]
            );
        }

        if ($request->getUri()->getPath() === '/1.1/statuses/user_timeline.json') {
            return $this->response(200, $this->tweets(), [], 'OK');
        }

        return $this->response(400, 'Not found');
    }

    private function tweets(): array
    {
        return SearchTweetsResponse::tweets();
    }
}
