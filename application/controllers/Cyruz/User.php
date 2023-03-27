<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CyruzController
{
  // main
  public function __construct()
  {
    parent::__construct();
    // load frontend
    $this->data['frontend'] = [
      [ 'Cyruz/User/list/index.html', 'Cyruz/User/list/index.js' ],
      [ 'Cyruz/User/view/index.html', 'Cyruz/User/view/index.js' ],
      [ 'Cyruz/User/create/index.html', 'Cyruz/User/create/index.js' ],
      [ 'Cyruz/User/update/index.html', 'Cyruz/User/update/index.js' ],
      [ 'Cyruz/User/delete/index.html', 'Cyruz/User/delete/index.js' ],
    ];
  }
  // index page
  public function index()
  {
    // set layout
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'User', 'index' ],
      'title'=> 'User Management',
    ];
    // set table information
    $this->data['table'] = [
      'header'=> 'User Management',
      'description'=> 'Todo management user for this website',
    ];

    $this->layout( $this->data );
  }

}