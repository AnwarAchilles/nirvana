<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_menu extends BaseApi
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


  public function composed_menu_GET()
  {
    $menus = [];

    $parents = $this->models->get([
      'join'=> ['menu'=> 'menu.id_menu = role_menu.id_menu'],
      'where'=> ['id_role'=>$this->id, 'id_parent'=>'0'],
      'order_by'=> ['menu.stack'=>'asc'],
    ])->result_array();

    foreach ($parents as $menu) {
      $childs = $this->models->get([
        'join'=> ['menu'=>'menu.id_menu = role_menu.id_menu'],
        'where'=> ['id_role'=> $this->id, 'id_parent'=>$menu['id_menu']],
        'order_by'=> ['menu.stack'=>'asc'],
      ])->result_array();

      foreach ($childs as $menu2) {
        $children = $this->models->get([
          'join'=> ['menu'=>'menu.id_menu = role_menu.id_menu'],
          'where'=> ['id_role'=> $this->id, 'id_parent'=>$menu2['id_menu']],
          'order_by'=> ['menu.stack'=>'asc'],
        ])->result_array();

        $rest = [];
        foreach ($children as $menu2x) {
          $menu2x['options'] = json_decode($menu2x['options']);
          $rest[] = $menu2x;
        }
        $menu2['childs'] = $rest;
        
        $menu2['options'] = json_decode($menu2['options']);
        $menu['childs'][] = $menu2;
      }

      $menu['options'] = json_decode($menu['options']);
      $menus[] = $menu;
    }
    
    $this->data = $menus;
    $this->return(200, '200 OK');
  }


  public function id_role_GET( $id_role )
  {
    $role_menu = $this->models->get([
      'where'=> ['id_role'=> $this->id],
    ])->result_array();
    
    for ($i=0; $i<count($role_menu); $i++) {
      $role_menu[$i]['options'] = json_decode( $role_menu[$i]['options'] );
    }

    $this->data = $role_menu;
    $this->return(200, '200 OK');
  }


  public function entries_POST()
  {
    $this->models->delete([
      'where'=> ['id_role'=> $this->method['id_role'], 'id_menu'=>$this->method['id_menu']],
    ]);

    if ($this->method['status'] !== 'false') {
      $data = [
        'id_role'=> $this->method['id_role'],
        'id_menu'=> $this->method['id_menu'],
        'options'=> json_encode($this->method['options']),
      ];
      $id = $this->models->create([ 'data'=> $data ]);
      $this->data = $id;
      $this->return(200, 'OK');
    }
  }
  

}