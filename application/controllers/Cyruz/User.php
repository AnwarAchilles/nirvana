<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CyruzController
{
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