<?php
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

function PUT(string $name): string
{

  $lines = file('php://input');
  $keyLinePrefix = 'Content-Disposition: form-data; name="';

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
function get_request_value($key, $default = null)
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