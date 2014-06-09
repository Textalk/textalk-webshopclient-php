<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\ApiClass;

$connection = new Connection(array('webshop' => 22222));
$assortment = new ApiClass('Assortment', $connection);

var_dump($assortment->getArticlegroupUids());
