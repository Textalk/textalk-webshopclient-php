<?php

namespace Textalk\WebshopClient\Mock;

/**
 * A mock connection to be used when testing.
 */
class ConnectionMock implements \Textalk\WebshopClient\ConnectionInterface {

  public $classes = array(); // Array of ApiClassMock instances
  public $instances = array(); // Array of ApiInstanceMock instance

  public function getApiClass($class) {
    if (isset($this->classes[$class])) return $this->classes[$class];
    throw new Exception("Unexpected class: $class");
  }

  public function getApiInstance($class, $uid) {
    if (isset($this->instances[$class][$uid])) return $this->classes[$class][$uid];
    throw new Exception("Unexpected instance. Class: $class UID: $uid");
  }

  public function getUri() { return 'mock://mock'; }

  public function __get($key) {
    if (isset($this->classes[$key])) return $this->classes[$key];
    throw new Exception("Unexpected get: $key");
  }
}
