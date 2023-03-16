<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


/* ---- ---- ---- ----
 * LOADER FOR CONTROLLER & MODEL
 * 
 * load all base, libraries, and helper here
 * ---- ---- ---- ---- */
$config['loader'] = [
  // todo load controller
  'api'           => [ 'BaseApi' ],

  // todo load controller
  'controller'    => [ 'BaseController', 'Cyruz/CyruzController' ],

  // todo load model
  'model'         => [ 'BaseModel' ],
  
  // todo load library
  'libraries'     => [ 'twig', 'curl', 'session', 'pdftools', 'office' ],
  
  // todo load helper
  'helper'        => [ 'text' ]
];


/* ---- ---- ---- ----
 * API CONFIGURATIONS
 * 
 * all apis configurations here
 * ---- ---- ---- ---- */

// set mode development | productions
$api['mode'] = 'productions';

// prefix table for table_id | id
$api['prefix_id'] = true;


/* ---- ---- ---- ----
 * CONTROLLER CONFIGURATIONS
 * 
 * all controllers configurations here
 * ---- ---- ---- ---- */

// controller frontend based
$controller['frontend'] = 'twig'; # ( under development )

// layouting
$controller['layout']['default'] = [

  // todo use layout-draw as wrapper
  'draw'          => false,
  
  // todo set page title
  'title'         => 'Layouting Engine',
  
  // todo set page tab color
  'tab_color'     => '#000000',
  
  // todo set page name
  'name'          => 'Layouting Engine',
  
  // todo set page description
  'description'   => 'Layouting structure base twig engine',
  
  // todo setup stylesheet/css
  'stylesheet'    => [
    
    // source link
    'source'      => [
      'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons',
      base_url('/storage/vendor/@fortawesome/fontawesome-pro/css/all.min.css'),
      base_url('/storage/vendor/datatables-1.12.1/css/datatables.min.css'),
      base_url('/storage/vendor/select2/dist/css/select2.min.css'),
      base_url('/storage/css/cyruz.css'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,

  ],

  // todo setup javascript/js
  'javascript'    => [
    
    // source link
    'source'      => [
      base_url('/storage/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'),
      base_url('/storage/vendor/@fortawesome/fontawesome-pro/js/all.min.js'),
      base_url('/storage/vendor/jquery/dist/jquery.min.js'),
      base_url('/storage/vendor/datatables-1.12.1/js/datatables.min.js'),
      base_url('/storage/vendor/select2/dist/js/select2.full.min.js'),
      base_url('/storage/js/cyruz.js'),
      base_url('/storage/vendor/upup/upup.min.js'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,

  ],
];

// layouting
$controller['layout']['cyruz'] = [
  
  // todo use layout-draw as wrapper
  'draw'          => false,
  
  // todo set page title
  'title'         => 'Layouting Engine',
  
  // todo set page tab color
  'tab_color'     => '#000000',
  
  // todo set page name
  'name'          => 'Layouting Engine',
  
  // todo set page description
  'description'   => 'Layouting structure base twig engine',
  
  // todo setup stylesheet/css
  'stylesheet'    => [
    
    // source link
    'source'      => [
      'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons',
      base_url('/storage/vendor/@fortawesome/fontawesome-pro/css/all.min.css'),
      base_url('/storage/vendor/datatables-1.12.1/css/datatables.min.css'),
      base_url('/storage/vendor/select2/dist/css/select2.min.css'),
      base_url('/storage/css/cyruz.css'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,
  
  ],
  
  // todo setup javascript/js
  'javascript'    => [
    
    // source link
    'source'      => [
      base_url('/storage/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'),
      base_url('/storage/vendor/@fortawesome/fontawesome-pro/js/all.min.js'),
      base_url('/storage/vendor/jquery/dist/jquery.min.js'),
      base_url('/storage/vendor/datatables-1.12.1/js/datatables.min.js'),
      base_url('/storage/vendor/select2/dist/js/select2.full.min.js'),
      base_url('/storage/vendor/ckeditor5-build-classic/ckeditor.js'),
      base_url('/storage/vendor/chart.js/dist/chart.min.js'),
      base_url('/storage/js/cyruz.js'),
      base_url('/storage/vendor/upup/upup.min.js'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,
  
  ],
];

// report handling
$controller['report'] = [
  'viewer'        => true,
  'download'      => false,
  'layout'        => [
    'stylesheet'  => [
      'select'    => [4]
    ],
    'javascript'  => false
  ]
];






# PACKING CONFIG
$config['api'] = $api;
$config['controller'] = $controller;