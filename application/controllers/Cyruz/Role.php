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

    $this->layout( $this->data );
  }

}