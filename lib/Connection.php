<?php

namespace Textalk\WebshopClient;

use Tivoka\Client\Connection\WebSocket;

/**
 * A Connection is the main API-handler.
 *
 * One Connection holds a WebSocket with a context.
 *
 * You can let the Connection-class hold named instances by using Connection::getInstance('name')
 * with different contexts (or even different backend URLs).
 */
class Connection implements ConnectionInterface {
  //
  // Static
  //

  protected static $default_backend = 'wss://shop.textalk.se/backend/jsonrpc/v1/';

  /**
   * Make a new Connection
   *
   * @param  array   $context   Context parameters (like webshop, session, auth...)
   * @param  string  $backend  Backend URL for WebSocket
   * @param  array   $options
   *   Associative array containing:
   *   - headers:  Request headers to add/override.
   *   - timeout:  Timeout in seconds.
   */
  public function __construct($context = array(), $backend = null, $options = array()) {
    $this->backend = empty($backend) ? self::$default_backend : $backend;
    $this->context = $context;
    $this->options = $options;
  }

  /**
   * Get a named instance, or initialise it
   *
   * To avoid creating multiple connections to the same backend, use named instances to get your
   * handle.
   *
   * To keep things separated clearly, you could initialize the default instance with the needed
   * context parameters, and in other parts of the code just use getInstance.
   *
   * @param  string  $name     Handler to get named instances
   * @param  array   $context  Context parameters (like webshop, session, auth...)
   * @param  string  $backend  Backend URL
   * @param  array   $options
   *   Associative array containing:
   *   - headers:  Request headers to add/override.
   *   - timeout:  Timeout in seconds.
   */
  public static function getInstance($name = 'default', $context = array(), $backend = null,
                                     $options = array()) {
    static $instances = array();

    if (array_key_exists($name, $instances)) return $instances[$name];

    return $instances[$name] = new self($context, $backend);
  }

  //
  // Public
  //

  /**
   * Make a request to the backend.
   */
  public function call($method, $params = null) {
    if (!isset($this->connection)) $this->connect();

    $request = $this->connection->sendRequest($method, $params);

    if ($request->error) throw Exception::factory($this, $request);

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

  /**
   * Get an ApiClass for this connection.
   *
   * @param $class      string                            Class name
   * @return            Textalk\WebshopClient\ApiClass
   */
  public function getApiClass($class) {
    return new ApiClass($class, $this);
  }

  /**
   * Get an ApiInstance for this connection.
   *
   * @param $class      string                            Class name
   * @param $uid        string|integer                    The API instance UID
   * @return            Textalk\WebshopClient\ApiInstance
   */
  public function getApiInstance($class, $uid) {
    return new ApiInstance($class, $uid, $this);
  }

  public function getSession() {
    if (array_key_exists('session', $this->context)) return $this->context['session'];

    $this->context['session'] = $this->call('Session.getToken');
    /// @todo Call Context.set({"session":"53f32a921ad53"}) when that is available.
    $this->connection = null;

    return $this->context['session'];
  }

  public function getUri() {
    $backend_uri = $this->backend;
    if (!empty($this->context)) $backend_uri .= '?' . http_build_query($this->context);
    return $backend_uri;
  }

  //
  // Magic methods
  //

  /**
   * Get an ApiClass for this connection.
   *
   * @param $class      string                            Class name
   * @return            Textalk\WebshopClient\ApiClass
   */
  public function __get($class) {
    return new ApiClass($class, $this);
  }

  /**
   * Get an ApiInstance for this connection.
   *
   * @param $class      string                            Class name
   * @param $args       array                             The API instance UID as only item
   * @return            Textalk\WebshopClient\ApiInstance
   */
  public function __call($class, array $args) {
    if (count($args) !== 1) {
      throw new \InvalidArgumentException(
        'To get an ApiInstance from Connection, you must give UID as the only parameter.'
      );
    }

    $uid = array_shift($args);
    return new ApiInstance($class, $uid, $this);
  }

  //
  // Protected
  //

  protected $connection; ///< Tivoka\Connection
  protected $backend;    ///< string Backend URL (without context)
  protected $context;    ///< array  Associative array of context parameters

  /**
   * This creates the tivoka connection
   *
   * Actually, it doesn't really connect to the api endpoint, it JUST creates the tivoka connection.
   */
  protected function connect() {
    $backend_uri = $this->backend;
    if (!empty($this->context)) $backend_uri .= '?' . http_build_query($this->context);
    $this->connection = new WebSocket($backend_uri, $this->options);
  }
}
