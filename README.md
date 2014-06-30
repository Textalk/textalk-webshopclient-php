Textalk Webshop API-client
==========================

[![Build Status](https://travis-ci.org/Textalk/textalk-webshopclient-php.png)](https://travis-ci.org/Textalk/textalk-webshopclient-php)
[![Coverage Status](https://coveralls.io/repos/Textalk/textalk-webshopclient-php/badge.png)](https://coveralls.io/r/Textalk/textalk-webshopclient-php)

A library to simplify API-usage on Textalk Webshop API.


Examples:
---------

To get all articlegroups on root level on the Demoshop, you could do:

```php
<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\ApiClass;

$connection = Connection::getInstance('default', array('webshop' => 22222));
$assortment = new ApiClass('Assortment', $connection);

var_dump($assortment->getArticlegroupUids());

// Will produce:
// array(2) {
//   [0] =>
//   int(1347891)
//   [1] =>
//   int(1347897)
// }
```


To get the name of articlegroup with UID 1347897 on webshop 22222 in all languages:

```php
require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\Instance;

$connection   = Connection::getInstance('default', array('webshop' => 22222));
$articlegroup = new Instance('Articlegroup', 1347891, $connection);

var_dump($articlegroup->get('name'));

// Will produce:
// array(1) {
//   'name' =>
//   array(2) {
//     'sv' =>
//     string(4) "Herr"
//     'en' =>
//     string(3) "Men"
//   }
// }
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
      "textalk/webshop-client": "0.1.0"
    }

in your projects composer.json.
