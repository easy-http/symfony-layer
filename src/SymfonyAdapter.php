<?php

namespace EasyHttp\SymfonyLayer;

use EasyHttp\LayerContracts\Contracts\HttpClientAdapter;
use EasyHttp\LayerContracts\Contracts\HttpClientRequest;
use EasyHttp\LayerContracts\Contracts\HttpClientResponse;
use EasyHttp\LayerContracts\Exceptions\HttpClientException;
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
            $response = $this->client->request(
                $request->getMethod(),
                $request->getUri(),
                $this->buildOptions($request)
            );

            return new SymfonyResponse($response, $response->getContent());
        } catch (ClientException | RedirectionException $exception) {
            $response = $exception->getResponse();
        } catch (ServerException | TransportException | TransportExceptionInterface $exception) {
            throw new HttpClientException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return new SymfonyResponse($response);
    }

    private function buildOptions(HttpClientRequest $request): array
    {
        $options = [
            'timeout' => $request->getTimeout(),
            'verify_peer' => $request->isSSL()
        ];

        $this
            ->setHeaders($request, $options)
            ->setJson($request, $options)
            ->setQuery($request, $options)
            ->setSecurityContext($request, $options)
            ->setBasicAuth($request, $options);

        return $options;
    }

    private function setHeaders(HttpClientRequest $request, &$options): self
    {
        if ($request->hasHeaders()) {
            $options['headers'] = $request->getHeaders();
        }

        return $this;
    }

    private function setJson(HttpClientRequest $request, &$options): self
    {
        if ($request->hasJson()) {
            $options['json'] = $request->getJson();
        }

        return $this;
    }

    private function setQuery(HttpClientRequest $request, &$options): self
    {
        if ($request->hasQuery()) {
            $options['query'] = $request->getQuery();
        }

        return $this;
    }

    private function setSecurityContext(HttpClientRequest $request, &$options): self
    {
        if ($request->hasSecurityContext() && $request->getSecurityContext()->hasCertificate()) {
            $options['local_cert'] = $request->getSecurityContext()->getCertificate();
        }

        if ($request->hasSecurityContext() && $request->getSecurityContext()->hasPrivateKey()) {
            $options['local_pk'] = $request->getSecurityContext()->getPrivateKey();
        }

        return $this;
    }

    private function setBasicAuth(HttpClientRequest $request, &$options): self
    {
        if (count($request->getBasicAuth())) {
            $options['auth_basic'] = $request->getBasicAuth();
        }

        return $this;
    }
}
