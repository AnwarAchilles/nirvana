<?php


$config['layout'] = [
  
  // Name of the layout
  'name' => 'Nirvana',
  
  // Description of the layout
  'description'=> 'Website Starter App',
  
  // Path to the layout files (if applicable)
  'path' => '',

  // Whether to draw something
  'draw' => FALSE,

  // Whether to include head content
  'head' => FALSE,

  // Whether to set offline web page
  'offline'=> TRUE,

  // Source Configuration
  // Configuration for Stylesheet and JavaScript List
  'source'=> [
    
    // List of stylesheet URLs (if any)
    'stylesheet'=> [],
    
    // List of JavaScript files
    'javascript'=> [
      base_url('resource/javascript/nirvana-3.5.js'),
    ],

  ],

  // Whether to bundling or not
  'bundling'=> FALSE,

  // Bundle Configuration
  // This section defines options for bundling source files into a single file.
  'bundle'=> [
    
    // Bundle stylesheets into one file (set to TRUE to enable)
    'stylehseet'=> TRUE,

    // Bundle JavaScript files into one file (set to TRUE to enable)
    'javascript'=> TRUE,

  ],
  
];