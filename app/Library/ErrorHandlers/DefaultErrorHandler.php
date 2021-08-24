<?php

/**
 * class for handling all errors and exceptions 
 * 
 */


namespace App\Library\ErrorHandlers;

class DefaultErrorHandler implements IErrorHandler
{
  /**
   * regester default handler for php
   * 
   */
  public function registerHandler()
  {
    set_error_handler(array($this, 'errorHandler'));
  }
  /**
   * handle errro and send result to as response 
   * 
   * @param int $errno error number
   * @param string $errstr error message  
   * @param string $errfile file that error ocures 
   * @param string $errline  line number of file that error created  
   */
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