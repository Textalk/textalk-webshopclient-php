<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\ApiClass;

$connection = Connection::getInstance('default', array('webshop' => 22222));
$assortment = $connection->getApiClass('Assortment');

var_dump($assortment->getArticlegroupUids());
