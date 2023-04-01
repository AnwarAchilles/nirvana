<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Blank extends BaseController
{
  
  # main universal
  public function __constrcut()
  {
    parent::__constrcut();
    // do something for universal method
  }

  # default blank page
  public function index()
  {
    $data['layout'] = [
      'draw'=> FALSE,
      'source'=> [ 'blank' ],
    ];

    $this->layout($data);
  }

  # 404 - not found page
  public function not_found()
  {
    $data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Blank', 'not_found' ],
      'title'=> '404 - Not Found',
    ];

    $this->layout($data);
  }

  # 503 - under maintenance
  public function maintenance()
  {
    $data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Blank', 'maintenance' ],
      'title'=> 'Under Maintenance',
    ];

    $this->layout($data);
  }
}