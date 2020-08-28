<?php

namespace Tests\Mocks\Concerns;

use Psr\Http\Message\RequestInterface;

trait HasBearerAuthentication
{
    protected array $tokens = [
        'tGzv3JOkF0XG5Qx2TlKWIA'
    ];

    protected function isAuthTokenCorrect(RequestInterface $request): bool
    {
        $authorization = $request->getHeader('Authorization');
        $authorization = array_shift($authorization);

        return $this->validateToken(trim(strstr($authorization, ' ')));
    }

    protected function validateToken(string $token): bool
    {
        return in_array($token, $this->tokens);
    }
}
