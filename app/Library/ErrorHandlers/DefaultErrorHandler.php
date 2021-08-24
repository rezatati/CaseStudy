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
    header("Content-type: application/json", true, 500);
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      echo json_encode(['error_msg' => $errstr, 'file' => $errfile, 'line' => $errline]);
      exit();
    }
    ///todo Log Error 
    echo json_encode(['result' => 'Internal Server Error ']);
    exit();
  }
}