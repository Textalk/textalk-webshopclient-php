<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;
use Textalk\WebshopClient\Instance;

$connection   = Connection::getInstance('default', array('webshop' => 22222));
$articlegroup = new ApiInstance('Articlegroup', 1347891, $connection);

var_dump($articlegroup->get('name'));
