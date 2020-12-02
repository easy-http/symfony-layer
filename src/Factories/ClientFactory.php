<?php

namespace EasyHttp\SymfonyLayer\Factories;

use Symfony\Component\HttpClient\CurlHttpClient as SymfonyClient;
use Symfony\Component\HttpClient\MockHttpClient;

class ClientFactory
{
    public static function build($handler = null)
    {
        $instance = null;

        if ($handler) {
            $instance = new MockHttpClient($handler);
        } else {
            $instance = new SymfonyClient();
        }

        return $instance;
    }
}
