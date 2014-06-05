<?php

namespace Textalk\ApiClient;

/**
 * A class representing one instance in the API.
 *
 * API-methods can be called directly on this instance.
 *
 * Example:
 *
 * $connection = Connection::getDefault(array('webshop' => 22222));
 * $article = new Instance('Article', 12565609, $connection);
 *
 * $article_data = $article->get(array('name' => 'sv', 'articlegroup' => true));
 */
class Instance {
  /**
   * Get an instance representing one instance in the API.
   *
   * @param $class      string                            The API class name
   * @param $uid        string|integer                    The API instance UID
   * @param $connection Textalk\ApiClient\Connection|null The connection to use, or null for default
   */
  public function __construct($class, $uid, Connection $connection = null) {
    $this->class = $class;
    $this->uid   = $uid;
    $this->connection = $connection;
  }

  /**
   * Place all the API-instances methods on this instance magically.
   */
  public function __call($method, $params) {
    array_unshift($params, $this->uid);

    return $this->connection->call($this->class . '.' . $method, $params);
  }

  //
  // Protected
  //

  protected $class; ///< The API class name
  protected $uid;   ///< The API instance UID
  protected $connection; ///< The Textalk\ApiClient\Connection to use
}
