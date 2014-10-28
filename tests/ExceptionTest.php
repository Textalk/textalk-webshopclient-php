<?php

use Textalk\WebshopClient\Exception;
use Textalk\WebshopClient\ApiClass;
use Textalk\WebshopClient\ApiInstance;
use Textalk\WebshopClient\Connection;
use Tivoka\Client;

class ExceptionTest extends PHPUnit_Framework_TestCase {
  public function testUnspecifiedExceptionCode() {
    $connection = Client::connect('wss://shop.textalk.se/backend/jsonrpc/v1/');
    $request    = $connection->sendRequest('Foo', 'bar');

    $request->error = 424242;
    try {
      throw Exception::factory($connection, $request);
    }
    catch (Textalk\WebshopClient\Exception $e) {
      $this->assertInstanceOf('Textalk\WebshopClient\\Exception', $e);
    }

    $this->assertNotNull($e, 'A Textalk\WebshopClient\Exception should be thrown.');
  }

  public function testGetMessageWithData() {
    try {
      $connection = new Connection(array('webshop' => 22222));
      $new_order  = new ApiInstance('Order', null, $connection);
      $new_order->set(array('language' => 'foo')); // Will not validate
    }
    catch (Textalk\WebshopClient\Exception $e) {
      $string = $e->getMessage();
      $data   = $e->getData();

      $this->assertRegExp('/^Order.set: /', $string);
      $this->assertInternalType('array', $data);
    }

    $this->assertNotNull($e, 'A Textalk\WebshopClient\Exception should be thrown.');
  }
}
