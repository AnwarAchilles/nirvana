<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Deploy extends BaseController
{

  public $role = [];
  public $menu = [];
  public $user = [];
  public $role_menu = [];

  /* ---- ---- ---- ----
   * INDEX
   * ---- ---- ---- ---- */
  public function index()
  {
    $this->role();
    $this->menu();
    $this->user();
    $this->role_menu();
    $this->toasted();
    
    redirect( base_url('cyruz/auth') );
  }

  /* ---- ---- ---- ----
   * GENERATE ROLES
   * ---- ---- ---- ---- */
  public function role()
  {
    $this->db->query("TRUNCATE role");

    $this->role['admin'] = $this->api("POST", "role", [
      'name'=> 'Admin',
      'note'=> 'superuser/administrator for manage this webapps',
    ]);
    
    $this->role['guest'] = $this->api("POST", "role", [
      'name'=> 'Guest',
      'note'=> 'Nirvana guest/client for review the apps',
    ]);
  }

  /* ---- ---- ---- ----
   * GENERATE MENUS
   * ---- ---- ---- ---- */
  public function menu()
  {
    $this->db->query("TRUNCATE menu");

    $this->menu['dashboard'] = $this->api("POST", "menu", [
      'name'=> 'Dashboard',
      'child'=> 0,
      'stack'=> 1,
      'url'=> 'cyruz/dashboard',
      'icon'=> 'home',
      'color'=> 'text-primary',
    ]);
    $this->menu['administrator'] = $this->api("POST", "menu", [
      'name'=> 'Administrator',
      'child'=> 3,
      'stack'=> 2,
      'url'=> 'Administrator',
      'icon'=> 'folder-open',
      'color'=> 'text-secondary',
    ]);
    $this->menu['user'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 1,
      'name'=> 'User',
      'url'=> 'cyruz/user',
      'icon'=> 'user',
      'color'=> 'text-danger',
    ]);
    $this->menu['menu'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 2,
      'name'=> 'Menu',
      'url'=> 'cyruz/menu',
      'icon'=> 'folder-tree',
      'color'=> 'text-info',
    ]);
    $this->menu['role'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 3,
      'name'=> 'Role',
      'url'=> 'cyruz/role',
      'icon'=> 'sitemap',
      'color'=> 'text-warning',
    ]);
    $this->menu['product'] = $this->api("POST", "menu", [
      'name'=> 'Product',
      'child'=> 0,
      'stack'=> 3,
      'url'=> 'cyruz/product',
      'icon'=> 'box-open',
      'color'=> 'text-success',
    ]);
  }

  /* ---- ---- ---- ----
   * GENERATE USERS
   * ---- ---- ---- ---- */
  public function user()
  {
    $this->db->query("TRUNCATE user");

    $this->user['admin'] = $this->api("POST", "user", [
      'name'=> 'Admin',
      'email'=> 'admin@nirvana.com',
      'password'=> 'admin',
      'id_role'=>  $this->role['admin']['id'],
    ]);
  }

  /* ---- ---- ---- ----
   * GENERATE ROLE MENU
   * ---- ---- ---- ---- */
  public function role_menu()
  {
    $this->db->query("TRUNCATE role_menu");

    $this->role_menu['admin'] = [];
    $this->role_menu['guest'] = [];

    foreach ($this->menu as $menu) {
      $this->role_menu['admin'][] = $this->api("POST", "role_menu", [
        'id_role'=> $this->role['admin']['id'],
        'id_menu'=> $menu['id'],
        'options'=> json_encode([
          "view"=> true, "create"=> true, "update"=> true, "delete"=> true,
          "print"=> true, "import"=> true, "export"=> true, "format"=> true,
        ]),
      ]);
      $this->role_menu['guest'][] = $this->api("POST", "role_menu", [
        'id_role'=> $this->role['guest']['id'],
        'id_menu'=> $menu['id'],
        'options'=> json_encode([
          "view"=> true, "create"=> true, "update"=> true, "delete"=> true,
          "print"=> true, "import"=> true, "export"=> true, "format"=> true,
        ]),
      ]);
    }
  }

  /* ---- ---- ---- ----
   * GENERATE ROLE MENU
   * ---- ---- ---- ---- */
  public function toasted()
  {
    $this->db->query("TRUNCATE toasted");

    $this->toasted = $this->api("POST", "toasted", [
      'header'=> 'All Notifications Clearing',
      'message'=> 'Thanks for click',
      'icon'=> 'warning',
    ]);
  }
}