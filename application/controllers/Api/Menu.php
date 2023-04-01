<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends BaseApi
{

  # get menu composed by parent and childs 
  public function composed_menu_GET() {
    $menus = [];
    // get parents menu
    $parents = $this->models->get([
      'where'=> ['id_parent'=>0],
      'order_by'=> ['stack'=> 'asc']
    ])->result_array();
    // loopline to compose menu
    foreach ($parents as $row) {
      if ($row['id_parent']!==0) {
        $childs = $this->models->get([
          'where'=> ['id_parent'=>$row['id_menu']],
          'order_by'=> ['stack'=>'asc']
        ])->result_array();

        foreach ($childs as $xrow) {
          $xrow['childs'] = $this->models->get([
            'where'=> ['id_parent'=>$xrow['id_menu']],
            'order_by'=>['stack'=> 'asc']
          ])->result_array();

          $row['childs'][] = $xrow;
        }
      }
      $menus[] = $row;
    }
    // insert menus to output data api
    $this->data = $menus;
    // return status & message
    $this->return(200, 'Success Composed Menus');
  }

  # get menu composed by parent and childs with options 
  public function composed_menu_option_GET() {
    $menus = [];
    // get parents menu
    $parents = $this->models->get([
      'where'=> ['id_parent'=>0],
      'order_by'=> ['stack'=> 'asc']
    ])->result_array();

    // loopline to compose menu
    foreach ($parents as $row) {
      $row['options'] = $this->controller->api('GET', 'menu_option/map/'.$row['id_menu']);

      if ($row['id_parent']!==0) {
        $childs = $this->models->get([
          'where'=> ['id_parent'=>$row['id_menu']],
          'order_by'=> ['stack'=>'asc']
        ])->result_array();

        // loopline child 1
        foreach ($childs as $xrow) {
          $xrow['options'] = $this->controller->api('GET', 'menu_option/map/'.$xrow['id_menu']);
          $xrow['childs'] = $this->models->get([
            'where'=> ['id_parent'=>$xrow['id_menu']],
            'order_by'=>['stack'=> 'asc']
          ])->result_array();

          // loopline child 2
          foreach ($xrow['childs'] as $key => $xxrow) {
            $xxrow['options'] = $this->controller->api('GET', 'menu_option/map/'.$xrow['id_menu']);
            $xrow['childs'][$key] = $xxrow;
          }

          $row['childs'][] = $xrow;
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