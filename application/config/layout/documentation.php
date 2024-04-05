<?php


$config['layout'] = [
  
  // Name of the layout
  'name' => 'Nirvana',
  
  // Description of the layout
  'description'=> 'Website Starter App',
  
  // Path to the layout files (if applicable)
  'path' => 'documentation',

  // Whether to draw something
  'draw' => FALSE,

  // Whether to include head content
  'head' => FALSE,

  // Whether to set offline web page
  'offline'=> FALSE,

  // setup cache parse data
  'cache'=> FALSE,

  // Source Configuration
  // Configuration for Stylesheet and JavaScript List
  'source'=> [
    
    // List of stylesheet URLs (if any)
    'stylesheet'=> [
      resource('template/cyruz/fonts/cyruz.css'),
      resource('template/cyruz/vendor/select2/dist/css/select2.css'),
      resource('vendor/fontawesome-pro/css/fontawesome.css'),
      resource('vendor/fontawesome-pro/css/brands.css'),
      resource('vendor/fontawesome-pro/css/regular.css'),
      resource('vendor/fontawesome-pro/css/duotone.css'),
      resource('vendor/fontawesome-pro/css/light.css'),
      resource('vendor/fontawesome-pro/css/thin.css'),
      resource('vendor/fontawesome-pro/css/solid.css'),
      resource('vendor/fontawesome-pro/css/brands.css'),
      resource('template/cyruz/vendor/bootstrap/dist/css/bootstrap.css'),
      resource('template/cyruz/css/cyruz.css'),
    ],
    
    // List of JavaScript files
    'javascript'=> [
      resource('template/cyruz/vendor/jquery/dist/jquery.js'),
      resource('template/cyruz/vendor/bootstrap/dist/js/bootstrap.bundle.js'),
      resource('vendor/fontawesome-pro/js/fontawesome.js'),
      resource('vendor/fontawesome-pro/js/brands.js'),
      resource('template/cyruz/vendor/chart.js/dist/chart.min.js'),
      resource('template/cyruz/vendor/select2/dist/js/select2.js'),
      resource('template/cyruz/js/cyruz.js'),
      resource('javascript/nirvana-3.8.js'),
    ],

  ],

  // Bundle Configuration
  // This section defines options for bundling source files into a single file.
  'bundle'=> [

    // activate feature
    'active'=> FALSE,

    // process bundling while reload
    'process'=> FALSE,

    // filename prefix
    'filename'=> 'documentation',
    
    // Bundle stylesheets into one file (set to source)
    'stylehseet'=> [],

    // Bundle JavaScript files into one file (set source)
    'javascript'=> [],

  ],
  
];