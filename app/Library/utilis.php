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