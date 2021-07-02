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
