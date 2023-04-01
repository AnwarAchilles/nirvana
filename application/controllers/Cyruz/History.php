<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class History extends CyruzController
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
    $this->data['layout']['title'] = 'History Logs';
    $this->data['layout']['source'] = [ 'Cyruz', 'History', 'index' ];

    $this->data['table'] = [
      'header'=> 'History Logs',
      'description'=> 'Todo management user for this website',
    ];

    $this->layout( $this->data );
  }

}