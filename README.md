
[![Build Status](https://travis-ci.org/pimssas/pims-api-client-php.svg?branch=master)](https://travis-ci.org/pimssas/pims-api-client-php)  
  
Pims PHP API client  
=========================  
  
Simple PHP client for the Pims API ([documentation here](http://api.pims.io)).  
  
    
* [Installation](#installation)
* [Usage](#usage)
* [License](#license)

Requirement
-----

```json
php >= 7.0
```


Installation
-----

Use [Composer](https://getcomposer.org/) to install. Simply add this line to your `composer.json` file:
```json
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

First step, you have to create a `Client` instance.

```php
use Pims\Api\Client;

try {
    // Minimal setup
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
use Pims\Api\Resource;

try {
    // Display the label of the event by ID 2127
    $event = $client->getOne(
    	Resource::EVENTS,
    	2127);
    echo $event['label'];
    
    // Get the 10 last promotions applied to the event by ID 2127
    $promotions = $client->getAll(
       	Resource::EVENTS_PROMOTIONS,
       	[
       		':event_id' => 2127,
       		'sort'      => '-date', 
       		'page_size' => 10
       	]);
    
    // Get all events occuring in April 2018:
    $results = $client->getAll(
    	Resource::EVENTS,
    	[
    	    'from_date'	=> '2018-04-01',
    	    'to_date' 	=> '2018-04-30'
    	]);
    $events = $results;
    while ($results->hasLink('next')) {
    	$results = $client->getNext($results);
        $events = array_merge($events, $results);
    }
    
    // Delete the venue by ID 234
    $client->deleteOne(
     	Resource::VENUES,
       	234);
} catch (ClientException $e) {
    echo $e->getMessage();
}
```

License
-------

Copyright (c) 2010-2018 Pims SAS.
Released under the [MIT License](https://github.com/pimssas/pims-api-client-php/blob/master/LICENSE).
