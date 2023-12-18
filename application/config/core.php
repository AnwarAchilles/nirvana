<?php



/* ---- ---- ---- ----
 * API CONFIGURATIONS
 * 
 * all apis configurations here
 * ---- ---- ---- ---- */

// set mode development | production
$api['mode'] = 'development';

// JWT token expired set in milisecond
$api['token_expired'] = 3600;

// JWT token secret key
$api['token_secretkey'] = 'nirvana-anwar-achilles';




/* ---- ---- ---- ----
 * LOADER FOR CONTROLLER & MODEL
 * 
 * load all base, libraries, and helper here
 * ---- ---- ---- ---- */
$config['loader'] = [
  // todo load controller
  'api'           => [ 'BaseApi' ],

  // todo load controller
  'controller'    => [ 'BaseController' ],

  // todo select eloquent used Avenirer or Sujeet
  'eloquent'      => 'Avenirer',

  // todo load model
  'model'         => [ 'BaseModel' ],
  
  // todo load library
  // 'libraries'     => [],
  'libraries'     => [ 'layout', 'RequestHttp', 'minify', 'blaze', 'session' ],
  
  // todo load helper
  'helper'        => [ 'text' ]
];









/* SETUP CONFIGURATION */
$config['api'] = $api;