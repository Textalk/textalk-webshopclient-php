<?php

namespace Textalk\WebshopClient;

/**
 * (Should have been called Class, but that's a reserved word in PHP.)
 */
class ApiClass {
  /**
   * Get an instance representing an API class without UID.
   *
   * @param $class   string  Class name
   * @param $connection Textalk\WebshopClient\Connection|null The connection, or null for default
   */
  public function __construct($class, Connection $connection = null) {
    $this->class = $class;

    if ($connection === null) $this->connection = new Connection();
    else                      $this->connection = $connection;
  }

  /**
   * Making all API methods available on this instance magically.
   */
  public function __call($method, $params) {
    return $this->connection->call($this->class . '.' . $method, $params);
  }


  //
  // Protected
  //

  protected $class; ///< The API class name
}
