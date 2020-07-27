<?php

namespace Pleets\HttpClient\Clients;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use Pleets\HttpClient\Clients\Constants\Client;
use Symfony\Component\HttpClient\CurlHttpClient as SymfonyClient;
use Symfony\Component\HttpClient\MockHttpClient;

class ClientFactory
{
    public static function build(string $client, $handler = null)
    {
        $instance = null;

        switch ($client) {
            case Client::SYMFONY:
                if ($handler) {
                    $instance = new MockHttpClient($handler);
                } else {
                    $instance = new SymfonyClient();
                }

                break;

            case Client::GUZZLE:
            default:
                if ($handler) {
                    $handler  = HandlerStack::create($handler);
                    $instance = new GuzzleClient(['handler' => $handler]);
                } else {
                    $instance = new GuzzleClient();
                }

                break;
        }

        return $instance;
    }
}
