<?php

namespace Pleets\HttpClient\Clients;

use Pleets\HttpClient\Clients\Constants\Client;
use Pleets\HttpClient\Clients\Guzzle\Adapter as GuzzleAdapter;
use Pleets\HttpClient\Contracts\HttpClientAdapter;

class AdapterFactory
{
    public static function build(string $client, $handler = null): HttpClientAdapter
    {
        $adapter = null;

        switch ($client) {
            case Client::GUZZLE:
            default:
                $adapter = new GuzzleAdapter(ClientFactory::build($client, $handler));
                break;
        }

        return $adapter;
    }
}
