<?php

/**
 *  search array of strings in a string   
 * 
 * @param string  $string string to search in  
 * @param array  $search array to search   
 * @param bool  $caseInsensitive search should take place  case sensetive or not  
 */
function contains($string, array $search, $caseInsensitive = false)
{
  $exp = '/'
    . implode('|', array_map('preg_quote', $search))
    . ($caseInsensitive ? '/i' : '/');
  return preg_match($exp, $string) ? true : false;
}

function isOneOfThem($string, array $search)
{
  foreach ($search as $item) {
    if ($string == $item)
      return true;
  }
  return false;
}

/**
 * extract value from put data for given $key  
 * 
 * @param string $name final route 
 * @return string  retun value for given key
 */
function PUT(string $name): string
{

  $lines = file('php://input');
  $keyLinePrefix = 'Content-Disposition: form-data; name="';
  if (is_array($lines) && !strpos($lines[0], 'Content-Disposition')) {
    $result = json_decode($lines[0]);
    if ($result) {
      return $result->$name;
    }
  }
  $PUT = [];
  $findLineNum = null;

  foreach ($lines as $num => $line) {
    if (strpos($line, $keyLinePrefix) !== false) {
      if ($findLineNum) {
        break;
      }
      if ($name !== substr($line, 38, -3)) {
        continue;
      }
      $findLineNum = $num;
    } else if ($findLineNum) {
      $PUT[] = $line;
    }
  }

  array_shift($PUT);
  array_pop($PUT);

  return mb_substr(implode('', $PUT), 0, -2, 'UTF-8');
}

/**
 * extract value from request based on request method  
 * 
 * @param string $key  key of data  
 * @param mixed $default  if key not found return default value  
 */
function get_request_value($key, $default = null): mixed
{
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    }
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST[$key])) {
      return $_POST[$key];
    }
  }
  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    return PUT($key);
  }
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return $default;
}


/**
 * handle and send response for not found actions 
 * 
 * @param int $code  response status code
 * @param string $msg  error message
 * @param string $title  error title
 */
function notFoundError($code, $msg, $title = "Resource Not Found")
{
  header('Content-type:application/problem+json', true, $code);
  echo json_encode([
    "type" => "https://example.net/not-found",
    "title" => $title,
    'invalid-params' => [
      'item' => $msg
    ]
  ]);
  exit;
}


/**
 * generate validation errors and send it as response 
 * 
 * @param array $errors list of validation errors
 */
function returnValidatioErrors($errors)
{
  $errs = [];
  foreach ($errors as $key => $value) {
    $errs[] = [
      "name" => $key,
      "reason" => $value
    ];
  }
  header('Content-type:application/problem+json', true, 400);
  echo json_encode([
    "type" => "https://example.net/validation-error",
    "title" => "Your request parameters didn't validate.",
    'invalid-params' => $errs
  ]);
  exit;
}