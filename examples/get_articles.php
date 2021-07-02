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
