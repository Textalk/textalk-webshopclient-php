<?php

namespace Textalk\ApiClient;

use Tivoka\Client;

/**
 * A Connection is the main API-handler.
 *
 * One Connection holds a WebSocket with a context.
 *
 * Sometimes, you might want different contexted connections, e.g. one customer session and one
 * administrator context.  Then you might use one for default and
 */
class Connection {
  protected static $default_backend = 'wss://shop.textalk.se/backend/jsonrpc/v1/';
  protected static $instances = array();

  protected function __construct($name, $backend, $context) {
    $this->backend = $backend;
    $this->context = $context;

    self::$instances[$name] = $this;
  }

  public static function getDefault($context = array()) {
    if (isset(self::$instances['default'])) {
      $connection = self::$instances['default'];
      $connection->setContext($context);
      return $connection;
    }

    return new self('default', self::$default_backend, $context);
  }


  //
  // Public
  //

  /**
   * Make a request to the backend.
   */
  public function call($method, $params) {
    if (!isset($this->connection)) $this->connect();

    $request = $this->connection->sendRequest($method, $params);

    //var_dump($this->connection);
    //var_dump($request);

    if ($request->error) throw Exception::factory($this->connection, $request);

    return $request->result;
  }

  /**
   * Set the whole context.
   *
   * To set a single context parameter, use setContextParam
   *
   * @param $context  array  An associative array of context parameters.
   */
  public function setContext($context = array()) {
    if ($context == $this->context) return; // Nothing changed

    // For now, store context and drop current connection.  The new context will be applied when
    // connection is reopened.  In future, some context parameters can be changed with an API-call.
    $this->context    = $context;
    $this->connection = null;
  }


  //
  // Protected
  //

  protected $connection; ///< Tivoka\Connection
  protected $backend;    ///< string Backend URL (without context)
  protected $context;    ///< array  Associative array of context parameters

  protected function connect() {
    $backend_uri = $this->backend;
    if (!empty($this->context)) $backend_uri .= '?' . http_build_query($this->context);
    $this->connection = Client::connect($backend_uri);
  }
}
