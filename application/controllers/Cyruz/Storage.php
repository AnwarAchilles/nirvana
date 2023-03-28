<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Storage extends CyruzController
{
  // index page
  public function index()
  {
    // set layout
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Storage', 'index' ],
      'title'=> 'Storage Management',
    ];
    $this->data['table'] = [
      'header'=> 'Storage Management',
      'description'=> 'Todo management file for this website',
    ];

    $this->layout( $this->data );
  }

}