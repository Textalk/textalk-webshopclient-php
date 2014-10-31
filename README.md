Textalk Webshop API-client
==========================

[![Build Status](https://travis-ci.org/Textalk/textalk-webshopclient-php.png)](https://travis-ci.org/Textalk/textalk-webshopclient-php)
[![Coverage Status](https://coveralls.io/repos/Textalk/textalk-webshopclient-php/badge.png)](https://coveralls.io/r/Textalk/textalk-webshopclient-php)

A library to simplify API-usage on Textalk Webshop API.


Examples:
---------

To get the name in Swedish (sv) of the first 2 articlegroups on the Demoshop, you could do:

```php
<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;

$api = Connection::getInstance('default', array('webshop' => 22222));

var_dump(
  $api->Articlegroup->list(
    array("name" => "sv", "uid" => true),
    array("limit" => 2)
  )
);

// Will produce:
// array(2) {
//   [0] =>
//   array(2) {
//     'name' =>
//     array(1) {
//       'sv' =>
//       string(4) "Herr"
//     }
//     'uid' =>
//     int(1347891)
//   }
//   [1] =>
//   array(2) {
//     'name' =>
//     array(1) {
//       'sv' =>
//       string(3) "Dam"
//     }
//     'uid' =>
//     int(1347897)
//   }
// }
```


You can save a handle to the API for a specific instance:

```php
$api = Connection::getInstance('default', array('webshop' => 22222));

// Save a handle to the API for a single Articlegroup:
$articlegroup = $api->Articlegroup(1347891);

// Get all names:
// These are all equivalent:
//
//  * $api->Articlegroup(1347891)->get('name')
//  * $api->Articlegroup->get(1347891, 'name')
//  * $api->call('Articlegroup.get', array(1347891, 'name'))
var_dump(
  $articlegroup->get('name')
);

// Will produce:
// array(1) {
//   'name' =>
//   array(2) {
//     'en' =>
//     string(3) "Men"
//     'sv' =>
//     string(4) "Herr"
//   }
// }
```


If you mess up, you will get specific exceptions and see the actual request:

```php
// This line won't actually DO anything, so it won't crasch:
$scissor = $api->IDontKnow("What I'm doing");

// But this will:
$scissor->run();

// -->
// PHP Fatal error:  Uncaught exception 'Textalk\WebshopClient\Exception\MethodNotFound' with message 'IDontKnow.run: Method not found: No API for IDontKnow
// On request: {"jsonrpc":"2.0","method":"IDontKnow.run","id":"7089b561-9252-4a0a-b45b-15a873509571","params":["What I'm doing"]}' in /home/liljegren/textalk-webshopclient-php/lib/Exception.php:32
```


Named Connections
-----------------

Normally, you only want ONE connection in your application.  You can use Connection::getInstance()
and get the same Connection every time.

You can let the Connection-class hold named instances by using Connection::getInstance('name')
with different contexts (or even different backend URLs).  E.g.:

    $admin_connection = Connection::getInstance('admin', array('auth' => $auth_token));

... elsewhere in the code you can get the connection with:

    $admin_connection = Connection::getInstance('admin');


Installing
----------

Preferred way to install is with [Composer](https://getcomposer.org/).

Just add

    "require": {
      "textalk/webshop-client": "0.2.*"
    }

in your projects composer.json.


Changelog
---------

0.2.1

 * Using tivoka 3.4.*, avoiding stability-level dev for dependencies.

0.2.0

 * Explicitly always using WebSocket connection.
 * Adding possibility to set options for connection, like headers and timeout.
 * Removed case-juggling in class name magic; use correct casing!
