# PHP HTTP Client integrations

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=pleets_php-http-clients&metric=alert_status)](https://sonarcloud.io/dashboard?id=pleets_php-http-clients)

Integration of several HTTP Clients in a unique interface.

The available clients in this version are the following:

- Guzzle v7.0
- Symfony v5.1

You can download this project as follows.

```bash
git clone git@github.com:pleets/php-http-clients.git
```

# Usage

## Simple requests

You can execute a simple request through the Standard class. 

```php
use Pleets\HttpClient\Standard;
use Pleets\HttpClient\Clients\Constants\Client;

$client = new Standard(Client::GUZZLE);   // using Guzzle
$response = $client->request('GET', 'https://api.ratesapi.io/api/2020-07-24/?base=USD');

$response->getStatusCode(); // 200
$response->response();      // JSON
```

## Prepared requests

A prepared request is a more flexible way to generate requests through any client.

```php
use Pleets\HttpClient\Standard;
use Pleets\HttpClient\Clients\Constants\Client;

$client = new Standard(Client::SYMFONY);   // using Symfony

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