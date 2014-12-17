<?php

namespace Textalk\WebshopClient;

/**
 * Interface for API Connections.
 */
interface ConnectionInterface {
  public function getApiClass($class);
  public function getApiInstance($class, $uid);
  public function getUri();
}
