<?php

namespace Textalk\WebshopClient\Mock;

/**
 * A mock Tivoka client request class.
 */
class RequestMock extends \Tivoka\Client\Request {

  public $target = null;

  /**
   * Mock sending data to target.
   * @param mixed $target Remote end-point definition
   */
  public function sendTo($target) {
    $this->target = $target;
  }

}
