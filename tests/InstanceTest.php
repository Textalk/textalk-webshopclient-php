<?php

use Textalk\ApiClient\Instance;
use Textalk\ApiClient\Connection;

class InstanceTest extends PHPUnit_Framework_TestCase {
  public function testInstantiationOfFoo() {
    $instance = new Instance('Foo', 'bar');

    // No classname validation is done before first call.
    $this->assertInstanceOf('Textalk\ApiClient\Instance', $instance);
  }

  public function testGetArticleData() {
    $connection = Connection::getDefault(array('webshop' => 22222));
    $article = new Instance('Article', 12565609, $connection);

    // Get all data
    $article_data = $article->get(array('name' => 'sv', 'articlegroup' => true));
    $this->assertArrayHasKey('name', $article_data);
  }


  // testUsingDefaultConnection
}
