<?php

namespace App\Library\ErrorHandlers;

interface IErrorHandler
{
  public function registerHandler();
  public function errorHandler($errno, $errstr, $errfile, $errline);
}
