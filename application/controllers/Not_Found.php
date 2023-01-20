<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Not_Found extends BaseController
{
  // index page
  public function index()
  {
    $data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Not_Found', 'index' ],
      'title'=> '404 - Not Found',
    ];

    $this->layout($data);
  }
}