<?php


/* ---- ---- ---- ----
 * SUMMARY
 * 
 * formatByte
 * printCallStack
 * snake2pascal
 * pascal2snake
 * removeHtmlComment
 * precent2decimal
 * convertTimestamp
 * textCutWord
 * 
 * ---- ---- ---- ---- */


/**
 * (Abandoned Do Not Use)
 */
if (!function_exists('__')) {
  function __( $language )
  {
    $query = explode('.', $language);
    $line = $query[0];
    unset($query[0]);
    $arrayNested = implode("']['", $query);
    $code = "lang('$line')['$arrayNested']";
    try {
      return eval("return $code;");
    }catch(Exception $e) {}
  }
}



/**
 * Format a given size in bytes to a human-readable string.
 *
 * @param int|float $size The size in bytes to be formatted.
 * @param int $precision The number of decimal places to round the result to (default is 2).
 *
 * @return string The formatted size with appropriate units (e.g., "2.34 MB").
 */
if (!function_exists('formatBytes')) {
  function formatBytes($size, $precision = 2)
  {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
  }
}


/**
 * Prints the call stack of the current execution.
 */
if (!function_exists('printCallStack')) {
  function printCallStack()
  {
    $trace = debug_backtrace();
    array_shift($trace); // Remove the call to print_call_stack itself

    echo "Call Stack:<br>";
    foreach ($trace as $index => $call) {
      echo "{$index}: ";
      if (isset($call['class'])) {
        echo "{$call['class']}{$call['type']}";
      }
      echo "{$call['function']}()<br>";
    }
  }
}


/**
 * Pascal to Snake Case Converter
 *
 * This function converts a PascalCase string to snake_case.
 *
 * @param string $input The input string in PascalCase.
 *
 * @return string The converted string in snake_case.
 */
if (!function_exists('pascal2snake')) {
  function pascal2snake($input)
  {
      return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
  }
}

/**
 * Snake Case to PascalCase Converter
 *
 * This function converts a snake_case string to PascalCase.
 *
 * @param string $input The input string in snake_case.
 *
 * @return string The converted string in PascalCase.
 */
if (!function_exists('snake2pascal')) {
  function snake2pascal($input)
  {
      // Capitalize the first letter of each word after underscores
      return str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
  }
}



if (!function_exists('removeHtmlComment')) {
  function removeHtmlComment($string)
  {
    // Use preg_replace to remove HTML comments
    return preg_replace('/<!--(.*?)-->/s', '', $string);
  }
}


/**
 * Mengubah persentase menjadi desimal.
 *
 * @param float $persentase Persentase yang ingin diubah.
 * @return float Desimal yang setara dengan persentase.
 */
if (!function_exists('percent2decimal')) {
  function percent2decimal($persentase) {
      return $persentase / 100;
  }
}





function convertTimestamp($timestamp) {
  $seconds = $timestamp % 60;
  $minutes = floor(($timestamp % 3600) / 60);
  $hours = floor(($timestamp % 86400) / 3600);
  $days = floor(($timestamp % 2592000) / 86400);
  $months = floor($timestamp / 2592000);

  return "$months months, $days days, $hours hours, $minutes minutes, $seconds seconds";
}


function removeHTML($input) {
  return preg_replace('/<\/?[^>]+(>|$)/', '', $input);
}


function textCutWord($text, $maxWords) {
  $words = explode(' ', $text);
  if (count($words) <= $maxWords) {
      return $text; // If the text has fewer words than $maxWords, return the original text.
  }
  $truncatedText = implode(' ', array_slice($words, 0, $maxWords));
  return $truncatedText;
}

function cleanUrl($url) {
  // Remove extra slashes
  $cleanUrl = preg_replace('#/+#', '/', $url);

  // Remove double slashes after the domain
  $cleanUrl = preg_replace('#^(https?://[^/]+)/+#i', '$1/', $cleanUrl);
  $cleanUrl = str_replace('https:/', 'https://', $cleanUrl);

  return $cleanUrl;
}