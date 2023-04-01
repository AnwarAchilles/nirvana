<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Role extends CyruzController
{

  # main
  public function __construct()
  {
    parent::__construct();

    $this->data['layout']['draw'] = TRUE;
  }

  # index page
  public function index()
  {
    $this->data['layout']['title'] = 'Role Access';
    $this->data['layout']['source'] = [ 'Cyruz', 'Role', 'index' ];

    $this->data['table'] = [
      'header'=> 'Role Access',
      'description'=> 'Todo management user for this website',
    ];

    $this->data['menus'] = $this->api('GET', 'menu/composed_menu_option');
    $this->data['options'] = [
      'detail'=> 0, 'create'=> 0,
      'update'=> 0, 'delete'=> 0, 
      'print'=> 0, 'import'=> 0, 
      'export'=> 0, 'format'=> 0,
    ];

    $this->layout( $this->data );
  }

}