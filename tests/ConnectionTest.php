<?php

use Textalk\WebshopClient\Connection;

class ConnectionTest extends PHPUnit_Framework_TestCase {
  public function testGetDefaultInstance() {
    $connection = Connection::getInstance();
    $this->assertInstanceOf('Textalk\WebshopClient\Connection', $connection);

    $connection2 = Connection::getInstance();
    $this->assertSame($connection, $connection2);
  }

  public function testSimpleCall() {
    $connection = new Connection();

    $connection->setContext(array('webshop' => 22222));

    $article_uids = $connection->call('Assortment.getArticleUids', array(1347898));

    $this->assertInternalType('array', $article_uids);
  }

  public function testMethodNotFound() {
    try {
      $connection = new Connection(array('webshop' => 22222));
      $dummy = $connection->call('Foo.bar', array());
    }
    catch (Textalk\WebshopClient\Exception\MethodNotFound $e) {
      $request_json = $e->getRequestJson();
      $this->assertInternalType('string', $request_json);

      $request = json_decode($request_json);

      // $request should be the jsonrpc
      $this->assertInternalType('object', $request);
      $this->assertObjectHasAttribute('id',      $request);
      $this->assertObjectHasAttribute('jsonrpc', $request);
      $this->assertEquals('Foo.bar', $request->method);

      $this->assertEquals('wss://shop.textalk.se/backend/jsonrpc/v1/?webshop=22222',
                          $e->getRequestUri());
    }

    $this->assertNotNull($e, 'A Textalk\WebshopClient\Exception\MethodNotFound should be thrown.');
  }

  public function testChangeContext() {
    $connection = new Connection();

    // Make a call to be sure there is a connection.
    $initial_context = $connection->call('Context.get', array(true));
    $this->assertEmpty($initial_context['webshop']);

    $connection->setContext(array('webshop' => 22222));
    $new_context = $connection->call('Context.get', array(true));
    $this->assertSame(22222, $new_context['webshop']);
  }
}
