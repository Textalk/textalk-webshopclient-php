<?php

namespace Textalk\WebshopClient;

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
  public static function factory(\Textalk\WebshopClient\ConnectionInterface $connection,
                                 \Tivoka\Client\Request $request) {
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

  /// Get the data part of the rpc error.
  public function getData() { return $this->request->errorData; }

  /// Get the message part of the rpc error.
  public function getRpcMessage() { return $this->request->errorMessage; }

  //
  // Protected
  //

  protected $connection; ///< Textalk\WebshopClient\ConnectionInterface The connection that was used.
  protected $request;    ///< Tivoka\Client\Request The request that failed.

  /**
   * Construct a new Exception.
   *
   * This can't be protected since it extends Exception, but it shouldn't be used.  Use factory.
   */
  public function __construct(\Textalk\WebshopClient\ConnectionInterface $connection,
                              \Tivoka\Client\Request $request) {
    $this->request    = $request;
    $this->connection = $connection;

    parent::__construct($this->formatMessage(), $request->error);
  }

  protected function formatMessage() {
    $method  = $this->request->method;
    $message = $this->request->errorMessage;
    $data    = $this->request->errorData;

    $phpversion = explode('.', phpversion());

    $output = "$method: $message";

    if (!empty($data)) {
      $json_encode_flags = 0;

      if (($phpversion[0] === '5' && $phpversion[1] >= 4) || $phpversion[0] > 5) {
        $json_encode_flags = JSON_PRETTY_PRINT;
      }

      $output .= "\nError data: " . json_encode($data, $json_encode_flags);
    }
    $output .= "\nOn request: " . $this->request->request;

    return $output;
  }
}
