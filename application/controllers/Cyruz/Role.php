<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Role extends CyruzController
{

  // index page
  public function index()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Role', 'index' ],
      'title'=> 'Role Access',
    ];
    $this->data['table'] = [
      'header'=> 'Role Access',
      'description'=> 'Todo management user for this website',
    ];
    $this->data['options'] = [
      'view'=> [],
      'create'=> [],
      'update'=> [],
      'delete'=> [],
      'print'=> [],
      'import'=> [],
      'export'=> [],
      'format'=> [],
    ];

    $this->data['menus'] = $this->api('GET', 'menu/composed_menu');
    $this->data['options'] = [
      'view'=> 0, 'create'=> 0,
      'update'=> 0, 'delete'=> 0, 
      'print'=> 0, 'import'=> 0, 
      'export'=> 0, 'format'=> 0,
    ];

    $this->layout( $this->data );
  }

  public function menu( $id_role )
  {
    $menu = $this->api('GET', 'menu/composed_menu');
    $menu_select = $this->api('GET', 'role_menu/composed_menu/'.$id_role);
    
    $selected_menu = [];
    // for ($i=0; $i<count($menu); $i++) {
    //   $selected_menu[] = [];
    //   if (isset($menu_select[$i])) {
    //     if ($menu[$i]['name'] == $menu_select[$i]['name']) {
    //       $selected_menu[$i] = 'selected';
    //     }
    //   }
    // }
    $defaultOptions = [
      'view'=> 0, 'create'=> 0,
      'update'=> 0, 'delete'=> 0, 
      'print'=> 0, 'import'=> 0, 
      'export'=> 0, 'format'=> 0,
    ];

    foreach ($menu as $i=>$parent) {
      
      if (isset($menu_select[$i])) {
        $menu[$i]['options'] = $menu_select[$i]['options'];
        $menu[$i]['checked'] = 1;
      }else {
        $menu[$i]['options'] = $defaultOptions;
        $menu[$i]['checked'] = 0;
      }

      if (isset($parent['childs'])) {
        foreach ($parent['childs'] as $ii=>$child) {

          if (isset($menu_select[$i]['childs'][$ii])) {
            $menu[$i]['childs'][$ii]['options'] = $menu_select[$i]['childs'][$ii]['options'];
            $menu[$i]['childs'][$ii]['checked'] = 1;
          }else {
            $menu[$i]['childs'][$ii]['options'] = $defaultOptions;
            $menu[$i]['childs'][$ii]['checked'] = 0;
          }

          if (isset($child['childs'])) {
            foreach ($child['childs'] as $iii=>$child2) {
    
              if (isset($menu_select[$i]['childs'][$ii]['childs'][$iii])) {
                $menu[$i]['childs'][$ii]['childs'][$iii]['options'] = $menu_select[$i]['childs'][$ii]['childs'][$iii]['options'];
                $menu[$i]['childs'][$ii]['childs'][$iii]['checked'] = 1;
              }else {
                $menu[$i]['childs'][$ii]['childs'][$iii]['options'] = $defaultOptions;
                $menu[$i]['childs'][$ii]['childs'][$iii]['checked'] = 0;
              }
            }
          }
        }
      }
    }

    $this->data['menu'] = $menu;

    echo $this->twig->render('cyruz/role/modal/menu-list.html', $this->data, true);
  }

}