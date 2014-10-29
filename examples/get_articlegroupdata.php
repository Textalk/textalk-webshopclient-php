<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;

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
