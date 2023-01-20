<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_Menu extends BaseApi
{
  

  public function sidebar_GET()
  {
    $parent = $this->models->get([
      'join'=> [ 'menu'=> 'menu.id_menu = role_menu.id_menu' ],
      'where'=> [ 'id_role'=> $this->id, 'id_parent'=>"0" ]
    ])->result_array();
    $child = $this->models->get([
      'join'=> [ 'menu'=> 'menu.id_menu = role_menu.id_menu' ],
      'where'=> [ 'id_role'=> $this->id, 'id_parent !='=>"0" ]
    ])->result_array();
    
    $menu = [];
    foreach ($parent as $parent_key=>$parent_val) {
      foreach ($child as $child_key=>$child_val) {
        if ($child_val['id_parent'] == $parent_val['id_menu']) {
          $parent_val['child'][] = $child_val;
        }
      }
      $menu[] = $parent_val;
    }

    $this->data = $menu;
    $this->return(200);
  }
  

}