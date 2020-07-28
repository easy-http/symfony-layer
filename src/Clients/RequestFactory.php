<?php

namespace Pleets\HttpClient\Clients;

use Pleets\HttpClient\Clients\Constants\Client;
use Pleets\HttpClient\Clients\Guzzle\Request as GuzzleRequest;
use Pleets\HttpClient\Clients\Symfony\Request as SymfonyRequest;
use Pleets\HttpClient\Contracts\HttpClientRequest;

class RequestFactory
{
    public static function build(string $client, string $method, string $uri): HttpClientRequest
    {
        $request = null;

        switch ($client) {
            case Client::SYMFONY:
                $request = new SymfonyRequest($method, $uri);
                break;

            case Client::GUZZLE:
            default:
                $request = new GuzzleRequest($method, $uri);
                break;
        }

        return $request;
    }
}
