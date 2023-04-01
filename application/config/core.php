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
  'controller'    => [ 'BaseController', 'Cyruz/CyruzController', 'Guest/GuestController', 'Seent/SeentController', ],

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
      base_url('/resource/vendor/@fortawesome/fontawesome-pro/css/all.min.css'),
      base_url('/resource/vendor/datatables-1.12.1/css/datatables.min.css'),
      base_url('/resource/vendor/select2/dist/css/select2.min.css'),
      base_url('/resource/css/cyruz.css'),
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
      base_url('/resource/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'),
      base_url('/resource/vendor/@fortawesome/fontawesome-pro/js/all.min.js'),
      base_url('/resource/vendor/jquery/dist/jquery.min.js'),
      base_url('/resource/vendor/datatables-1.12.1/js/datatables.min.js'),
      base_url('/resource/vendor/select2/dist/js/select2.full.min.js'),
      base_url('/resource/js/cyruz.js'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,

  ],
];

// layouting
$controller['layout']['Cyruz'] = [
  
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
      base_url('/resource/vendor/@fortawesome/fontawesome-pro/css/all.min.css'),
      base_url('/resource/vendor/datatables-1.12.1/css/datatables.min.css'),
      base_url('/resource/vendor/select2/dist/css/select2.min.css'),
      base_url('/resource/css/cyruz.css'),
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
      base_url('/resource/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'),
      base_url('/resource/vendor/@fortawesome/fontawesome-pro/js/all.min.js'),
      base_url('/resource/vendor/jquery/dist/jquery.min.js'),
      base_url('/resource/vendor/datatables-1.12.1/js/datatables.min.js'),
      base_url('/resource/vendor/select2/dist/js/select2.full.min.js'),
      base_url('/resource/vendor/ckeditor5-build-classic/ckeditor.js'),
      base_url('/resource/vendor/chart.js/dist/chart.min.js'),
      base_url('/resource/js/cyruz.js'),
    ],
    
    // todo validate all source
    'validate'    => false,
    
    // todo auto builder extends layout[source]
    'builder'     => true,
  
  ],
];

// layouting
$controller['layout']['Pringo'] = [
  
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
      base_url('resource/pringo/css/vendor/icofont.min.css'),
      base_url('resource/pringo/css/plugins/animate.min.css'),
      base_url('resource/pringo/css/plugins/swiper-bundle.min.css'),
      base_url('resource/pringo/css/plugins/aos.css'),
      base_url('resource/pringo/css/plugins/selectric.css'),
      base_url('resource/pringo/css/style.css'),
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
      base_url('resource/pringo/js/vendor/vendor.min.js'),
      base_url('resource/pringo/js/plugins/plugins.min.js'),
      base_url('resource/pringo/js/ajax-contact.js'),
      base_url('resource/pringo/js/plugins/aos.js'),
      base_url('resource/pringo/js/plugins/waypoints.js'),
      base_url('resource/pringo/js/plugins/jquery.selectric.min.js'),
      base_url('resource/pringo/js/main.min.js'),
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