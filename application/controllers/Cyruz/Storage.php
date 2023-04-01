<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Storage extends CyruzController
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
    $this->data['layout']['title'] = 'Storage Management';
    $this->data['layout']['source'] = [ 'Cyruz', 'Storage', 'index' ];

    $this->data['table'] = [
      'header'=> 'Storage Management',
      'description'=> 'Todo management file for this website',
    ];

    $this->layout( $this->data );
  }

}