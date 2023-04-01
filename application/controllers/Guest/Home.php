<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends GuestController
{

  # initialize universal data
  public function __construct()
  {
    parent::__construct();

    $this->data['layout']['module'] = 'Pringo';
    $this->data['layout']['layout'] = 'Pringo/layout';
    $this->data['layout']['source'] = [ 'Pringo', 'Home', 'index' ];
  }

  # index page
  public function index()
  {
    $this->data['layout']['draw'] = true;
    $this->data['layout']['title'] = 'Preparation Proschool';

    $this->layout( $this->data );
  }
}