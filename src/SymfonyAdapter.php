<?php

namespace EasyHttp\SymfonyLayer;

use EasyHttp\SymfonyLayer\SymfonyResponse;
use EasyHttp\SymfonyLayer\Contracts\HttpClientAdapter;
use EasyHttp\SymfonyLayer\Contracts\HttpClientRequest;
use EasyHttp\SymfonyLayer\Contracts\HttpClientResponse;
use EasyHttp\SymfonyLayer\Exceptions\HttpClientException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyAdapter implements HttpClientAdapter
{
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function request(HttpClientRequest $request): HttpClientResponse
    {
        try {
            $response = $this->client->request($request->getMethod(), $request->getUri(), $request->options());

            return new SymfonyResponse($response, $response->getContent());
        } catch (ClientException | RedirectionException $exception) {
            $response = $exception->getResponse();
        } catch (ServerException | TransportException | TransportExceptionInterface $exception) {
            throw HttpClientException::fromThrowable($exception);
        }

        return new SymfonyResponse($response);
    }
}
