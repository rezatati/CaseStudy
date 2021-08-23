<?php

namespace Library\ErrorHandlers;

class DefaultErrorHandler implements IErrorHandler
{
  public function registerHandler()
  {
    set_error_handler(array($this, 'errorHandler'));
  }

  public function errorHandler($errno, $errstr, $errfile, $errline)
  {
    header("application/html", true, 500);
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      echo $errstr . '<br>' . $errfile . "  Line : $errline";
    }
    ///todo Log Error 
    echo 'Internal Server Error ';
    exit();
  }
}