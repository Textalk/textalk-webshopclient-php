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

  public function testGetSession() {
    $connection = new Connection(array('webshop' => 22222));

    // Make a call to be sure there is a connection.
    $initial_context = $connection->call('Context.get', array(true));
    $this->assertEmpty($initial_context['session']);

    $session1 = $connection->getSession();
    $this->assertNotEmpty($session1);

    $context1 = $connection->call('Context.get', array(true));
    $this->assertEquals($session1, $context1['session']);

    // Make sure it won't change the session once gotten.
    $session2 = $connection->getSession();
    $context2 = $connection->call('Context.get', array(true));
    $this->assertEquals($session1, $session2);
    $this->assertEquals($session2, $context2['session']);
  }

  public function testHttpCall() {
    $connection = new Connection(array(), 'http://shop.textalk.se/backend/jsonrpc/v1');
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testHttpsCall() {
    $connection = new Connection(array(), 'https://shop.textalk.se/backend/jsonrpc/v1');
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testWsCall() {
    $connection = new Connection(array(), 'ws://shop.textalk.se/backend/jsonrpc/v1');
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testWssCall() {
    $connection = new Connection(array(), 'wss://shop.textalk.se/backend/jsonrpc/v1');
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testHttpCallWithHeaders() {
    $path = 'http://shop.textalk.se/backend/jsonrpc/v1';
    $options = array('headers' => array('foo' => 'bar', 'boo' => 'far'));
    $connection = new Connection(array(), $path, $options);
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testHttpsCallWithHeaders() {
    $path = 'https://shop.textalk.se/backend/jsonrpc/v1';
    $options = array('headers' => array('foo' => 'bar', 'boo' => 'far'));
    $connection = new Connection(array(), $path, $options);
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testWsCallWithHeaders() {
    $path = 'ws://shop.textalk.se/backend/jsonrpc/v1';
    $options = array('headers' => array('foo' => 'bar', 'boo' => 'far'));
    $connection = new Connection(array(), $path, $options);
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  public function testWssCallWithHeaders() {
    $path = 'wss://shop.textalk.se/backend/jsonrpc/v1';
    $options = array('headers' => array('foo' => 'bar', 'boo' => 'far'));
    $connection = new Connection(array(), $path, $options);
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  /**
   * @expectedException RuntimeException
   */
  public function testUnsupportedSchemeCall() {
    $connection = new Connection(array(), 'unsupported://shop.textalk.se/backend/jsonrpc/v1');
    $result = $connection->call('Webshop.get', array(22222, 'uid'));
    $this->assertEquals(22222, $result['uid']);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testMagicCallShouldFailWithoutUid() {
    $api = new Connection(array('webshop' => 22222));
    $instance = $api->Foo();
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testMagicCallShouldFailWithSeveralUids() {
    $api = new Connection(array('webshop' => 22222));
    $instance = $api->Foo(1, 2);
  }
}
