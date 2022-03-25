<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo-320.png"></p>

<p align="center">
<a href="https://travis-ci.com/easy-http/symfony-layer"><img src="https://travis-ci.com/easy-http/symfony-layer.svg?branch=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/symfony-layer"><img src="https://img.shields.io/scrutinizer/g/easy-http/symfony-layer.svg" alt="Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/symfony-layer/?branch=master"><img src="https://scrutinizer-ci.com/g/easy-http/symfony-layer/badges/coverage.png?b=master" alt="Code Coverage"></a>
</p>

# Symfony Layer

This is an HTTP layer for Symfony Client. For more layers see [Easy Http](https://github.com/easy-http).

<a href="https://sonarcloud.io/dashboard?id=easy-http_symfony-layer"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_symfony-layer&metric=security_rating" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_symfony-layer"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_symfony-layer&metric=bugs" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_symfony-layer"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_symfony-layer&metric=code_smells" alt="Bugs"></a>

This library supports the following versions of Symfony Http Client.

- Symfony Http Client v5.0 or later

# Installation

Use following command to install this library:

```bash
composer require easy-http/symfony-layer
```

# Usage

## Simple requests

You can execute a simple request as follows.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();
$response = $client->call('GET', 'https://api.ratesapi.io/api/2020-07-24/?base=USD');

$response->getStatusCode(); // 200
$response->parseJson();     // array
```

## Prepared requests

A prepared request is a more flexible way to generate a request. You can use the `setQuery` method
to specify request query.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();

$client->prepareRequest('POST', 'https://api.ratesapi.io/api/2020-07-24/');
$client->getRequest()->setQuery(['base' => 'USD']);
$response = $client->execute();

$response->getStatusCode(); // 200
$response->parseJson();     // array
```

Also, you can use the `setJson` method to set a json string as the body.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();

$client->prepareRequest('POST', 'https://jsonplaceholder.typicode.com/posts');
$client->getRequest()->setJson([
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1,
]);
$response = $client->execute();

$response->getStatusCode(); // 201
$response->parseJson();     // array
```

## HTTP Authentication

Actually this library supports basic authentication natively.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();

$client->prepareRequest('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token');
$user = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
$pass = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';
$client->getRequest()->setBasicAuth($user, $pass);
$client->getRequest()->setQuery(['grant_type' => 'client_credentials']);
$response = $client->execute();

$response->getStatusCode(); // 200
$response->parseJson();     // array
```
