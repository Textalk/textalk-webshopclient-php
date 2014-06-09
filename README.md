Textalk Webshop API-client
==========================

A library to simplify API-usage on Textalk Webshop API.


Examples:
---------

To get all articlegroups on root level on the Demoshop, you could do:

```php
<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\ApiClass;

$connection = new Connection(array('webshop' => 22222));
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


To get the name of article with UID 12565609 on webshop 22222 in all languages:

```php
require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\Instance;

$connection   = new Connection(array('webshop' => 22222));
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
