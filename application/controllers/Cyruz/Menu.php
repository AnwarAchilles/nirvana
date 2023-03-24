<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Menu extends CyruzController
{
  // index page
  public function index()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Menu', 'index' ],
      'title'=> 'Menu Sidebar',
    ];
    $this->data['table'] = [
      'header'=> 'Menu Sidebar',
      'description'=> 'Todo management user for this website',
    ];

    $this->layout( $this->data );
  }

  public function list()
  {
    $this->data['menu'] = $this->api('GET', 'menu/composed_menu');
    echo $this->twig->render('cyruz/menu/index-list.html', $this->data, true);
  }

}