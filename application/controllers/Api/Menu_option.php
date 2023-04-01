<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_option extends BaseApi
{
  
  public function id_menu_GET( $id_menu )
  {
    $this->data = $this->models->get([
      'where'=> [
        'id_menu'=> $id_menu
      ]
    ])->result_array();
    $this->return(200);
  }


  # todo mapping data by id_menu
  public function map_GET( $id_menu )
  {
    // set maps
    $maps = [];
    // load options
    $options = $this->models->get([
      'where'=> [
        'id_menu'=> $id_menu
      ]
    ])->result_array();
    // loopline and insert to maps
    foreach( $options as $option) {
      $maps[ $option['option'] ] = 0;
    }
    // output data
    $this->data = $maps;
    $this->return(200);
  }


  

}