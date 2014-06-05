<?php

use Textalk\ApiClient\Exception;
use Textalk\ApiClient\ApiClass;
use Textalk\ApiClient\Instance;
use Textalk\ApiClient\Connection;
use Tivoka\Client;

class ExceptionTest extends PHPUnit_Framework_TestCase {
  public function testUnspecifiedExceptionCode() {
    $connection = Client::connect('wss://shop.textalk.se/backend/jsonrpc/v1/');
    $request    = $connection->sendRequest($method, $params);

    $request->error = 424242;
    try {
      throw Exception::factory($connection, $request);
    }
    catch (Textalk\ApiClient\Exception $e) {
      $this->assertInstanceOf('Textalk\ApiClient\\Exception', $e);
    }

    $this->assertNotNull($e, 'A Textalk\ApiClient\Exception should be thrown.');
  }

  public function testToStringWithData() {
    try {
      $connection = Connection::getDefault(array('webshop' => 22222));
      $new_order  = new Instance('Order', null, $connection);
      $new_order->set(array('language' => 'foo')); // Will not validate
    }
    catch (Textalk\ApiClient\Exception $e) {
      $string = "$e";
      $data   = $e->getData();

      $this->assertRegExp('/^Order.set: /', $string);
      $this->assertInternalType('array', $data);
    }

    $this->assertNotNull($e, 'A Textalk\ApiClient\Exception should be thrown.');
  }
}
