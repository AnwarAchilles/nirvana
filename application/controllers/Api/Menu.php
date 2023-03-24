<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends BaseApi
{

  # get menu composed by parent and childs 
  public function composedMenu_GET() {
    // get parents menu
    $parents = $this->models->get([
      'where'=> ['id_parent'=>0],
      'order_by'=> ['thread'=> 'asc']
      ])->result_array();
    // loopline to compose menu
    $menus = [];
    foreach ($parents as $row) {
      if ($row['id_parent']!==0) {
        $childs = $this->models->get([
          'where'=> ['id_parent'=>$row['id_menu']],
          'order_by'=> ['thread'=>'asc']
          ])->result_array();
        foreach ($childs as $xrow) {
          $xrow['child'] = $this->models->get([
            'where'=> ['id_parent'=>$xrow['id_menu']],
            'order_by'=>['thread'=> 'asc']
            ])->result_array();
          $row['child'][] = $xrow;
        }
      }
      $menus[] = $row;
    }
    // insert menus to output data api
    $this->data = $menus;
    // return status & message
    $this->return(200, 'Success Composed Menus');
  }

}