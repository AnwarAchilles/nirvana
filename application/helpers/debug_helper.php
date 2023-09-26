<?php

use AnwarAchilles\PHPTracer;


/* ---- ---- ---- ----
 * SUMMARY
 * 
 * debug
 * dd
 * 
 * ---- ---- ---- ---- */



/**
 * Debugging function for displaying data.
 *
 * @param mixed $data The data to be debugged.
 */
if (!function_exists('debug')) {
  function debug($data) {
    $trace = new PHPTracer;
    $trace->run($data);
  }
}


/**
 * Debugging function for displaying data and halting execution.
 *
 * @param mixed $data The data to be debugged.
 */
if (!function_exists('dd')) {
  function dd($data) {
    $trace = new PHPTracer;
    $trace->run($data);
  }
}
