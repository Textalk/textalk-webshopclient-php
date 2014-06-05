<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\ApiClient\Connection;
use Textalk\ApiClient\ApiClass;

$connection = Connection::getDefault(array('webshop' => 22222));
$assortment = new ApiClass('Assortment', $connection);

var_dump($assortment->getArticlegroupUids());
