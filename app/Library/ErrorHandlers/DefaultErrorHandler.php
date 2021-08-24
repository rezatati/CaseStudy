<?php

namespace App\Library\ErrorHandlers;

class DefaultErrorHandler implements IErrorHandler
{
  public function registerHandler()
  {
    set_error_handler(array($this, 'errorHandler'));
  }

  public function errorHandler($errno, $errstr, $errfile, $errline)
  {

    $params = [];
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $params = ['error_msg' => $errstr, 'file' => $errfile, 'line' => $errline];
    }
    ///todo Log Error 

    header('Content-type:application/problem+json', true, 500);
    echo json_encode([
      "type" => "https://example.net/internal-server-error",
      "title" => "Internal Server Error",
      'invalid-params' => $params
    ]);
    exit;
  }
}