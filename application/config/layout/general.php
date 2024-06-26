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

  // setup cache parse data
  'cache'=> FALSE,

  // Source Configuration
  // Configuration for Stylesheet and JavaScript List
  'source'=> [
    
    // List of stylesheet URLs (if any)
    'stylesheet'=> [],
    
    // List of JavaScript files
    'javascript'=> [
      base_url('resource/javascript/nirvana-3.8.js'),
    ],

  ],

  // Bundle Configuration
  // This section defines options for bundling source files into a single file.
  'bundle'=> [

    // activate feature
    'active'=> TRUE,

    // process bundling while reload
    'process'=> FALSE,

    // filename prefix
    'filename'=> 'general',
    
    // Bundle stylesheets into one file (set to source)
    'stylesheet'=> [
      'layout.css',
      'welcome.css',
    ],

    // Bundle JavaScript files into one file (set source)
    'javascript'=> [
      'layout.js',
      'welcome.js'
    ],

  ],
  
];