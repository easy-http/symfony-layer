# PHP HTTP Client integrations

<p align="center">
<a href="https://travis-ci.org/easy-http/symfony-layer"><img src="https://travis-ci.org/easy-http/symfony-layer.svg?branch=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/symfony-layer"><img src="https://img.shields.io/scrutinizer/g/easy-http/symfony-layer.svg" alt="Code Quality"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_symfony-layer"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_symfony-layer&metric=security_rating" alt="Bugs"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/symfony-layer/?branch=master"><img src="https://scrutinizer-ci.com/g/easy-http/symfony-layer/badges/coverage.png?b=master" alt="Code Coverage"></a>
</p>

Integration of several HTTP Clients in a unique interface.

The available clients in this version are the following:

- Guzzle v7.0
- Symfony v5.1

You can download this project as follows.

```bash
git clone git@github.com:easy-http/symfony-layer.git
```

# Usage

## Simple requests

You can execute a simple request through the Standard class. 

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();
$response = $client->request('GET', 'https://api.ratesapi.io/api/2020-07-24/?base=USD');

$response->getStatusCode(); // 200
$response->response();      // JSON
```

## Prepared requests

A prepared request is a more flexible way to generate requests through any client.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();

$client->prepareRequest('POST', 'https://jsonplaceholder.typicode.com/posts');
$client->setJson([
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1,
]);
$response = $client->execute();

$response->getStatusCode(); // 201
$response->response();      // JSON
```

## HTTP Authentication

Actually this library supports basic authentication natively.

```php
use EasyHttp\SymfonyLayer\SymfonyClient;

$client = new SymfonyClient();

$client->prepareRequest('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token');
$user = 'AeA1QIZXiflr1_-r0U2UbWTziOWX1GRQer5jkUq4ZfWT5qwb6qQRPq7jDtv57TL4POEEezGLdutcxnkJ';
$pass = 'ECYYrrSHdKfk_Q0EdvzdGkzj58a66kKaUQ5dZAEv4HvvtDId2_DpSuYDB088BZxGuMji7G4OFUnPog6p';
$client->setBasicAuth($user, $pass);
$client->setQuery(['grant_type' => 'client_credentials']);
$response = $client->execute();

$response->getStatusCode(); // 200
$response->response();      // JSON
```