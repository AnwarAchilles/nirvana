<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth extends CyruzController
{
  public $auth = TRUE;
  // index page
  public function index()
  {
    $this->login();
  }

  // todo login user
  public function login()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Auth', 'login' ],
      'title'=> 'Authentications Login',
    ];

    $this->layout( $this->data );
  }

  // todo register user
  public function register()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Auth', 'register' ],
      'title'=> 'Authentications Register',
    ];

    $this->layout( $this->data );
  }

  // todo logout user
  public function logout()
  {
    session_unset();
    session_destroy();
    redirect(base_url('cyruz/auth'));
  }

  // todo generate super user
  public function deploy_website()
  {
    // generate admin
    $role = $this->api("POST", "role", [
      'name'=> 'Admin',
      'note'=> 'superuser/administrator for manage this webapps',
    ]);
    // generate guest
    $roleGuest = $this->api("POST", "role", [
      'name'=> 'Guest',
      'note'=> 'Nirvana guest/client for review the apps',
    ]);
    // generate user
    $user = $this->api("POST", "user", [
      'name'=> 'Admin',
      'email'=> 'admin@nirvana.com',
      'password'=> 'admin',
      'id_role'=>  $role[0],
    ]);

    
    
    $menu = $this->api("POST", "menu", [
      'name'=> 'Dashboard',
      'url'=> 'cyruz/dashboard',
      'icon'=> 'home',
      'color'=> 'text-primary',
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $role[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> false,
        "create"=> false,
        "update"=> false,
        "delete"=> false,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $roleGuest[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> false,
        "create"=> false,
        "update"=> false,
        "delete"=> false,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);


    $menu = $this->api("POST", "menu", [
      'name'=> 'Administrator',
      'url'=> 'Administrator',
      'icon'=> 'folder-open',
      'color'=> 'text-danger',
    ]);

    $menu = $this->api("POST", "menu", [
      'name'=> 'User',
      'url'=> 'cyruz/user',
      'icon'=> 'user',
      'color'=> 'text-danger',
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $role[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true, "create"=> true, "update"=> true, "delete"=> true,
        "print"=> false, "import"=> false, "export"=> false, "format"=> false,
      ]),
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $roleGuest[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true, "create"=> true, "update"=> true, "delete"=> true,
        "print"=> false, "import"=> false, "export"=> false, "format"=> false,
      ]),
    ]);

    $menu = $this->api("POST", "menu", [
      'name'=> 'Menu',
      'url'=> 'cyruz/menu',
      'icon'=> 'folder-tree',
      'color'=> 'text-info',
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $role[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $roleGuest[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);

    $menu = $this->api("POST", "menu", [
      'name'=> 'Role',
      'url'=> 'cyruz/role',
      'icon'=> 'sitemap',
      'color'=> 'text-warning',
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $role[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $roleGuest[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> false,
        "import"=> false,
        "export"=> false,
        "format"=> false,
      ]),
    ]);

    $menu = $this->api("POST", "menu", [
      'name'=> 'Product',
      'url'=> 'cyruz/product',
      'icon'=> 'box-open',
      'color'=> 'text-success',
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $role[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> true,
        "import"=> true,
        "export"=> true,
        "format"=> true,
      ]),
    ]);
    $role_menu = $this->api("POST", "role_menu", [
      'id_role'=> $roleGuest[0],
      'id_menu'=> $menu[0],
      'options'=> json_encode([
        "view"=> true,
        "create"=> true,
        "update"=> true,
        "delete"=> true,
        "print"=> true,
        "import"=> true,
        "export"=> true,
        "format"=> true,
      ]),
    ]);
    

    // redirect
    redirect( base_url('cyruz/auth') ); 
  }

}