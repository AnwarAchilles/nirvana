<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Deploy extends CyruzController
{
  
  public $auth = TRUE;

  public $role = [];
  public $menu = [];
  public $menu_option = [];
  public $user = [];
  public $role_menu = [];
  public $toasted = [];
  public $company = [];
  public $history = [];

  /* ---- ---- ---- ----
   * INDEX
   * ---- ---- ---- ---- */
  public function index()
  {
    $this->role();
    $this->user();
    $this->menu();
    $this->company();
    $this->toasted();
    $this->history();
    $this->role_menu();
    $this->menu_option();
    
    redirect( base_url('Cyruz/Auth') );
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
      'color'=> 'primary',
    ]);
    $this->menu['administrator'] = $this->api("POST", "menu", [
      'name'=> 'Administrator',
      'child'=> 5,
      'stack'=> 2,
      'url'=> 'Administrator',
      'icon'=> 'folder-open',
      'color'=> 'secondary',
    ]);
    $this->menu['user'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 1,
      'name'=> 'User',
      'url'=> 'cyruz/user',
      'icon'=> 'user',
      'color'=> 'danger',
    ]);
    $this->menu['menu'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 2,
      'name'=> 'Menu',
      'url'=> 'cyruz/menu',
      'icon'=> 'folder-tree',
      'color'=> 'info',
    ]);
    $this->menu['role'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 3,
      'name'=> 'Role',
      'url'=> 'cyruz/role',
      'icon'=> 'sitemap',
      'color'=> 'warning',
    ]);
    $this->menu['storage'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 4,
      'name'=> 'Storage',
      'url'=> 'cyruz/storage',
      'icon'=> 'shelves',
      'color'=> 'primary',
    ]);
    $this->menu['history'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 5,
      'name'=> 'History',
      'url'=> 'cyruz/history',
      'icon'=> 'calendar-clock',
      'color'=> 'secondary',
    ]);
    $this->menu['company'] = $this->api("POST", "menu", [
      'id_parent'=> $this->menu['administrator']['id'],
      'child'=> 0,
      'stack'=> 6,
      'name'=> 'Company',
      'url'=> 'cyruz/company',
      'icon'=> 'city',
      'color'=> 'danger',
    ]);
    $this->menu['product'] = $this->api("POST", "menu", [
      'name'=> 'Product',
      'child'=> 0,
      'stack'=> 3,
      'url'=> 'cyruz/product',
      'icon'=> 'box-open',
      'color'=> 'success',
    ]);

  }

  /* ---- ---- ---- ----
   * GENERATE ROLE MENU
   * ---- ---- ---- ---- */
  public function menu_option()
  {
    $this->db->query("TRUNCATE menu_option");

    $menus = $this->api('GET', 'menu');
    $options = [ 'detail', 'create', 'update', 'delete', 'print', 'import', 'export', 'format'];
    
    foreach ($menus as $menu) {
      foreach ($options as $option) {
        $this->api("POST", "menu_option", [
          'id_menu'=> $menu['id_menu'],
          'option'=> $option,
        ]);
        sleep(1);
      }
    }
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
    $this->user['guest'] = $this->api("POST", "user", [
      'name'=> 'Guest',
      'email'=> 'guest@nirvana.com',
      'password'=> 'guest',
      'id_role'=>  $this->role['guest']['id'],
    ]);
  }

  /* ---- ---- ---- ----
   * GENERATE ROLE MENU
   * ---- ---- ---- ---- */
  public function role_menu()
  {
    $this->db->query("TRUNCATE role_menu");

    foreach ($this->menu as $menu) {
      $this->role_menu[] = $this->api("POST", "role_menu", [
        'id_role'=> $this->role['admin']['id'],
        'id_menu'=> $menu['id'],
        'options'=> json_encode([
          "detail"=> true, "create"=> true, "update"=> true, "delete"=> true,
          "print"=> true, "import"=> true, "export"=> true, "format"=> true,
        ]),
      ]);
      $this->role_menu[] = $this->api("POST", "role_menu", [
        'id_role'=> $this->role['guest']['id'],
        'id_menu'=> $menu['id'],
        'options'=> json_encode([
          "detail"=> true, "create"=> false, "update"=> false, "delete"=> false,
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

  /* ---- ---- ---- ----
   * GENERATE COMPANY
   * ---- ---- ---- ---- */
  public function company()
  {
    $this->db->query("TRUNCATE company");

    $this->company = $this->api("POST", "company", [
      'name'=> 'Nirvana',
      'description'=> 'Starter Project',
    ]);
  }

  /* ---- ---- ---- ----
   * GENERATE HISTORY
   * ---- ---- ---- ---- */
  public function history()
  {
    $this->db->query("TRUNCATE history");
  }
}