
[![Build Status](https://travis-ci.org/pimssas/pims-api-client-php.svg?branch=master)](https://travis-ci.org/pimssas/pims-api-client-php)  
  
Pims PHP API client  
=========================  
  
Simple PHP client for the Pims API ([documentation here](http://api.pims.io)).  
  
* [Requirement](#requirement)    
* [Installation](#installation)
* [Usage](#usage)
* [License](#license)


Requirement
-----

```
php >= 7.0
```


Installation
-----

Use [Composer](https://getcomposer.org/) to install. Simply add this line to your `composer.json` file:
```
"require": {
    "pimssas/pims-api-client-php": "dev-master"
}
```
and then run:
```bash
$ composer update 
```

Or install directly from the command line:  
```bash
$ composer require pimssas/pims-api-client-php:"dev-master"
```


Usage
-----

First step, you have to create a `Client` instance:
```php
use Pims\Api\Client;
use Pims\Api\Exception\ClientException;

try {
    // Minimal setup...
    $client = new Client(
            'https://demo.pims.io/api',
            'username',
            'password');
    
    // ... or full setup, with language and version
    $client = new Client(
            'https://demo.pims.io/api',
            'username',
            'password',
            'fr',
            'v1');
} catch (ClientException $e) {
    echo $e->getMessage();
}
```

Then you can call it on various endpoints, like this:
```php
use Pims\Api\Endpoint;

try {
    // Get the label of the event by ID 2127
    $event = $client->getOne(
            Endpoint::EVENTS,
            2127);
    $label = $event->getProperty('label');

    // Get all events occuring in April 2018
    $results = $client->getAll(
            Endpoint::EVENTS,
            [
                    'from_date' => '2018-04-01',
                    'to_date'   => '2018-04-30'
            ]);
    $events = $results->getResource('events');
    while ($results->hasLink('next')) {
        $results = $client->getNext($results);
        $events = array_merge(
                $events,
                $results->getResource('events'));
    }

    // Get the first 3 channels applied to the event by ID 2127
    $promotions = $client->getAll(
            Endpoint::EVENTS_CHANNELS,
            [
                    ':event_id' => 2127,
                    'page_size' => 3
            ]);
    
    // Create a new streams group
    $client->postOne(
            Endpoint::STREAMS_GROUP,
            ['label' => 'Streams group test']);
           		
    // Update the status of the promotion by ID 1437
    $client->patchOne(
            Endpoint::PROMOTIONS,
            1437,
            ['status_id' => 'ENG']);

    // Delete the venue by ID 234
    $client->deleteOne(
            Endpoint::VENUES,
            234);
} catch (ClientException $e) {
    echo $e->getMessage();
}
```

License
-------

Copyright (c) 2010-2019 Pims SAS.
Released under the [MIT License](https://github.com/pimssas/pims-api-client-php/blob/master/LICENSE).
