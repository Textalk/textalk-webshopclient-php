<?php

namespace Textalk\ApiClient;

/**
 * (Should have been called Class, but that's a reserved word in PHP.)
 */
class ApiClass {
  /**
   * Get an instance representing an API class without UID.
   *
   * @param $class   string  Class name
   * @param $connection Textalk\ApiClient\Connection|null The connection to use, or null for default
   */
  public function __construct($class, Connection $connection = null) {
    $this->class = $class;

    if ($connection === null) $this->connection = Connection::getDefault();
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
