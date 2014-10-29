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


To get the name and UID of the first three articles in articlegroup 1347891:

```php
<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;

$api = Connection::getInstance('default', array('webshop' => 22222));

var_dump(
  $api->Article->list(
    array("name" => "sv", "uid" => true),
    array(
      "limit" => 3,
      "filters" => array(
        "/showInArticlegroups" => array("contains" => 1347891)
      )
    )
  )
);

// Will produce:
array(3) {
  [0] =>
  array(2) {
    'name' =>
    array(1) {
      'sv' =>
      string(16) "In hac habitasse"
    }
    'uid' =>
    int(12565483)
  }
  [1] =>
  array(2) {
    'name' =>
    array(1) {
      'sv' =>
      string(22) "Vivamus nec metus nunc"
    }
    'uid' =>
    int(12565484)
  }
  [2] =>
  array(2) {
    'name' =>
    array(1) {
      'sv' =>
      string(17) "Aenean quis purus"
    }
    'uid' =>
    int(12565485)
  }
}
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

0.2.0

 * Removed case-juggling in class name magic; use correct casing!
