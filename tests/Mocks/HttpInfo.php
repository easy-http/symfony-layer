<?php

namespace Tests\Mocks;

use EasyHttp\SymfonyLayer\Concerns\NeedsParseHeaders;
use Psr\Http\Message\RequestInterface;

class HttpInfo extends BaseMock
{
    use NeedsParseHeaders;

    public function __invoke(RequestInterface $request)
    {
        return $this->jsonResponse(
            200,
            [
                'method' => $request->getMethod(),
                'body' => $request->getBody()->getContents(),
                'uri' =>
                    $request->getUri()->getScheme() . '://' .
                    $request->getUri()->getHost() .
                    $request->getUri()->getPath(),
                'uriFragment' => [
                    'schema' => $request->getUri()->getScheme(),
                    'port' => $request->getUri()->getPort(),
                    'host' => $request->getUri()->getHost(),
                    'path' => $request->getUri()->getPath(),
                    'query' => $request->getUri()->getQuery(),
                ],
                'headers' => $this->parseHeaders($request->getHeaders()),
            ]
        );
    }
}
