<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Role extends CyruzController
{

  // index page
  public function index()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Role', 'index' ],
      'title'=> 'Role Access',
    ];
    $this->data['table'] = [
      'header'=> 'Role Access',
      'description'=> 'Todo management user for this website',
    ];
    $this->data['options'] = [
      'view'=> [],
      'create'=> [],
      'update'=> [],
      'delete'=> [],
      'print'=> [],
      'import'=> [],
      'export'=> [],
      'format'=> [],
    ];

    $this->data['menus'] = $this->api('GET', 'menu/composed_menu');
    $this->data['options'] = [
      'view'=> 0, 'create'=> 0,
      'update'=> 0, 'delete'=> 0, 
      'print'=> 0, 'import'=> 0, 
      'export'=> 0, 'format'=> 0,
    ];

    $this->layout( $this->data );
  }

}