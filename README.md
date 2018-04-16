
[![Build Status](https://travis-ci.org/pimssas/api-client.svg?branch=dev)](https://travis-ci.org/pimssas/api-client)  
  
Pims Api Client  
=========================  
  
Simple php client for Pims-Api ([Documentation](http://api.pims.io))  
  
    
* [Installation](#installation)
* [Usage](#usage)
* [License](#license)

Installation
-----
In Progress


Usage
-----

We will use [Pims-api](http://propilex.herokuapp.com) as an example API endpoint.

  
### Create the client

At a first step, we setup a `Client` instance.

```php
use Pims\Api\Client;

$client = new Client('https://demo.pims.io/api/v1', 'demo', 'q83792db2GCvgYVdKpU3yG3R');
```

### Get One Events

```php

$client = new Client('https://demo.pims.io/api/v1', 'demo', 'q83792db2GCvgYVdKpU3yG3R');  
  
$data = $client->getOne('/events', 2127);
```

### Get All Events
```php
$client = new Client('https://demo.pims.io/api/v1', 'demo', 'q83792db2GCvgYVdKpU3yG3R');  
  
$data = $client->getAll('/events');
```

If there is a second page with more documents, we can follow the `next` link.

```php
if ($data->hasLink('next')) {
    $nextDocumentsResource = $data->getFirstLink('next')->get();
}
```

License
-------

Copyright (c) 2010-2018 Pims SAS.
Released under the [Apache License](https://github.com/pimssas/pims-api-client-php/blob/dev/LICENSE).