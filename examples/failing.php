<?php

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use Textalk\WebshopClient\Connection;

$api = Connection::getInstance('default', array('webshop' => 22222));

// This line won't actually DO anything, so it won't crasch:
$scissor = $api->IDontKnow("What I'm doing");

// But this will:
$scissor->run();
