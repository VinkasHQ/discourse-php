# discourse-php

[![Tests](https://github.com/vinkashq/discourse-php/actions/workflows/tests.yml/badge.svg)](https://github.com/vinkashq/discourse-php/actions/workflows/tests.yml) [![Packagist Version](https://img.shields.io/packagist/v/vinkas/discourse?logo=packagist&logoColor=000000&label=version&labelColor=d9e0f3&color=f28d1a)](https://packagist.org/packages/vinkas/discourse)

PHP library to authenticate your forum using Discourse Connect

## Installation

The package [`vinkas/discourse`](https://packagist.org/packages/vinkas/discourse) can be installed using composer via packagist.

```
composer require vinkas/discourse
```

## Documentation

### Creating a Discourse client

```php
$discourse = new Vinkas\Discourse\Client('discourse.example.com', true);  // set true if ssl enabled
```

### Discourse Connect

```
$payload = $_GET['sso'];
$signature = $_GET['sig'];

$connect = $discourse->connect('SECRET', $payload, $signature);

if (!($connect->isValid())) {
    header("HTTP/1.1 403 Forbidden");
    echo("Bad Discourse Connect request");
    die();
}

$userParams = array(
    'external_id' => 'USER_ID',
    'email'     => 'EMAIL_ADDRESS',
    'username' => 'USERNAME',  // optional
    'name'     => 'FULL_NAME'  // optional
    // for more available fields https://meta.discourse.org/t/13045
);

$url = $connect->getResponseUrl($userParams)
header('Location: ' . $url);
exit(0);
```

Visit https://meta.discourse.org/t/setup-discourseconnect-official-single-sign-on-for-discourse-sso/13045 for more details about Discourse Connect.
