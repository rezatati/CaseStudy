<?php

namespace Library\ErrorHandlers;

class DefaultErrorHandler implements IErrorHandler
{
  public function registerHandler()
  {
    set_error_handler($this->errorHandler);
  }

  public function errorHandler($errno, $errstr, $errfile, $errline)
  {
    header("application/html", true, 500);
    ///todo Log Error 
    echo 'Internal Server Error ';
    exit();
  }
}