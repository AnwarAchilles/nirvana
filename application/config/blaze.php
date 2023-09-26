<?php





$config['blaze_bundle'] = ['apis', 'controllers'];

$config['blaze'] = [

  
  // apis
  'apis'=> [
    [
      'api.php',
      '@{filename}.php',
      'controllers/@api/v1'
    ]
  ],


  // controllers
  'controllers'=> [
    [
      'controller.php',
      '@{repositor}/@{filename}.php',
      '/controllers/'
    ]
  ],


  // models
  'models'=> [
    [
      'model.php',
      '_@{filename}.php',
      '/models/v1/'
    ]
  ],


  // views
  'views'=> [
    [
      'view.index.css',
      'index.css',
      '/views/@{repositor}/@{filename}/'
    ],
    [
      'view.index.html',
      'index.html',
      '/views/@{repositor}/@{filename}/'
    ],
    [
      'view.index.js',
      'index.js',
      '/views/@{repositor}/@{filename}/'
    ],
  ]

];