<?php

use Textalk\ApiClient\ApiClass;
use Textalk\ApiClient\Connection;

class ApiClassTest extends PHPUnit_Framework_TestCase {
  public function testInstatiate() {
    $class = new ApiClass('Foo');

    // No classname validation is done before first call.
    $this->assertInstanceOf('Textalk\ApiClient\ApiClass', $class);
  }

  public function testContextCalls() {
    $context = new ApiClass('Context');
    $context_data = $context->get(true);

    $this->assertInternalType('array', $context_data);
    $this->assertArrayHasKey('language', $context_data);
  }

  public function testGivenConnection() {
    $connection = Connection::getDefault();
    $context = new ApiClass('Context', $connection);
    $context_data = $context->get(true);

    $this->assertInternalType('array', $context_data);
    $this->assertArrayHasKey('language', $context_data);
  }
}
