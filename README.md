
[![Build Status](https://travis-ci.org/pimssas/pims-api-client-php.svg?branch=master)](https://travis-ci.org/pimssas/pims-api-client-php)  
  
Pims Api Client  
=========================  
  
Simple php client for Pims-Api ([Documentation](http://api.pims.io))  
  
    
* [Installation](#installation)
* [Usage](#usage)
* [License](#license)

Installation
-----

```bash
composer require pimssas/pims-api-client-php:"dev-master"
```


Usage
-----

We will use [Pims-api](https://api.pims.io/) as an example API endpoint.

  
### Create the client

At a first step, we setup a `Client` instance.

```php
use Pims\Api\Client;

try {
    $client = new Client('https://demo.pims.io/api/v1', 'user', 'password');
} catch (\Exception $e) {
    echo $e->getMessage();
}

```

### Get One Events

```php
$client = new Client('https://demo.pims.io/api/v1', 'user', 'password'); 
 
try {  
    $data = $client->getOne('/events', 2127);
} catch (ClientException $e) {
    echo $e->getMessage();
}
```

### Get All Events
```php
$client = new Client('https://demo.pims.io/api/v1', 'user', 'password'); 
 
try {
    $data = $client->getAll('/events');
} catch (ClientException $e) {
    echo $e->getMessage();
}
```

If there is a second page with more documents, we can follow the `next` link.

```php
if ($data->hasLink('next')) {
    $nextResource = $data->getFirstLink('next')->get();
}
```

License
-------

Copyright (c) 2010-2018 Pims SAS.
Released under the [MIT License](https://github.com/pimssas/pims-api-client-php/blob/master/LICENSE).
