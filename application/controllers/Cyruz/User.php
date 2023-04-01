<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CyruzController
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
    $this->data['layout']['title'] = 'User Management';
    $this->data['layout']['source'] = [ 'Cyruz', 'User', 'index' ];

    $this->data['table'] = [
      'header'=> 'User Management',
      'description'=> 'Todo management user for this website',
    ];

    $this->layout( $this->data );
  }

}