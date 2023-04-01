<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Menu extends CyruzController
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
    $this->data['layout']['title'] = 'Menu Sidebar';
    $this->data['layout']['source'] = [ 'Cyruz', 'Menu', 'index' ];

    $this->data['table'] = [
      'header'=> 'Menu Sidebar',
      'description'=> 'Todo management user for this website',
    ];

    $this->layout( $this->data );
  }

  public function list()
  {
    $this->data['menu'] = $this->api('GET', 'menu/composed_menu');
    echo $this->twig->render('Cyruz/Menu/index-list.html', $this->data, true);
  }

}