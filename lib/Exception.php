<?php

namespace Textalk\WebshopClient;

use Tivoka\Client\Request;

class Exception extends \Exception {
  protected static $class_by_code = array(
    -32700 => 'ParseError',
    -32600 => 'InvalidRequest',
    -32601 => 'MethodNotFound',
    -32602 => 'InvalidParams',
    -32603 => 'InternalError',
    -32000 => 'ServerError',
     1006  => 'AccessDenied',
     9001  => 'ValidationFailed',
  );

  /**
   * @param $request Tivoka\Client\Request
   *
   * @return Exception Generic or specific subclass
   */
  public static function factory(\Tivoka\Client\Connection\WebSocket $connection,
                                 Request $request) {
    if (array_key_exists($request->error, self::$class_by_code)) {
      $exception_class
        = 'Textalk\\WebshopClient\\Exception\\' . self::$class_by_code[$request->error];
    }
    else ($exception_class = 'Textalk\\WebshopClient\\Exception');

    throw new $exception_class($connection, $request);
  }

  public function getRequestJson() {
    return $this->request->request;
  }

  public function getRequestUri() {
    return $this->connection->getUri();
  }

  public function getData() { return $this->request->errorData; }

  public function __toString() {
    $method            = $this->request->method;
    $message           = $this->getMessage();
    $data              = $this->request->errorData;

    $phpversion = explode('.', phpversion());

    $output = "$method: $message";

    if (!empty($data)) {
      $json_encode_flags = 0;

      if (($phpversion[0] === '5' && $phpversion[1] >= 4) || $phpversion[0] > 5) {
        $json_encode_flags = JSON_PRETTY_PRINT;
      }

      $output .= "\n" . json_encode($data, $json_encode_flags);
    }

    return $output;
  }

  //
  // Protected
  //

  protected $request;    ///< Tivoka\Client\Request The request that failed
  protected $connection; ///< Tivoka\Client\Connection\WebSocket The connection that was used

  /**
   * Construct a new Exception.
   *
   * This can't be protected since it extends Exception, but it shouldn't be used.  Use factory.
   */
  public function __construct(\Tivoka\Client\Connection\WebSocket $connection, Request $request) {
    $this->request    = $request;
    $this->connection = $connection;

    parent::__construct($request->errorMessage, $request->error);
  }
}
