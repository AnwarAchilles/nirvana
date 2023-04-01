<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class CyruzController extends CoreController
{
  
  public $auth = FALSE;

  public $access = [
    'status'=> 'READY'
  ];
  
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
  public function __construct() {
    parent::__construct();
    // set credentials login
    $this->data['users'] = [];
    $this->data['users'] = $this->session->userdata("credentials");
    // set default layout
    $this->data['layout']['module'] = 'Cyruz';
    $this->data['layout']['layout'] = 'Cyruz/layout';
    // set sidebars
    $this->data['sidebars'] = @ $this->sidebars();
    // set access
    $this->data['access'] = $this->access;
    // do something here
    if ( ($this->auth) && ($this->uri->segment(3)!=='logout') ) {
      $this->credentials_check("/Cyruz", TRUE);
      // set redirect by status menu
      if ($this->access['status'] == 'MAINTENANCE') {
        redirect('/Blank/maintenance');
      }
    }else {
      $this->credentials_check("/Cyruz/Auth");
    }
    // get company
    $this->data['company'] = $this->api('GET', 'company/latest');
  }

  /* ---- ---- ---- ----
   * CREDENTIALS CHECK
   * ---- ---- ---- ---- */
  public function credentials_check( $redirect, $reverse=false ) {
    if ($reverse) {
      if ($this->session->userdata('credentials')) {
        redirect(base_url($redirect));
      }
    }else {
      if (!$this->session->userdata('credentials')) {
        redirect(base_url($redirect));
      }
    }
  }

  /* ---- ---- ---- ----
   * ACCESS PER USER
   * ---- ---- ---- ---- */
  public function sidebars()
  {
    if (!empty($this->data['users'])) {
      $result = [];
      $classCalled = strtolower(get_called_class());
      // load user
      $user = $this->api("GET", "user/".$this->data['users']['id_user']);
      // load menu sidebar
      $sidebars = $this->api("GET", "role_menu/composed_menu/".$user['id_role']);
      // output data
      foreach( $sidebars as $sidebar ) {
        $result[ strtolower($sidebar['name']) ] = $sidebar;
        
        if (strtolower($sidebar['name'])==$classCalled) {
          $this->access = $sidebar;
        }

        if (isset($sidebar['childs'])) {
          $result[ strtolower($sidebar['name']) ]['childs'] = [];
          
          foreach ( $sidebar['childs'] as $child ) {
            $result[ strtolower($sidebar['name']) ]
              ['childs'][ strtolower($child['name']) ] = $child;

            if (strtolower($child['name'])==$classCalled) {
              $this->access = $child;
            }

            if (isset($child['childs'])) {
              $result[ strtolower($sidebar['name']) ]['childs'][ strtolower($child['name']) ]['childs'] = [];

              foreach ( $child['childs'] as $child2 ) {
                $result[ strtolower($sidebar['name']) ]
                  ['childs'][ strtolower($child['name']) ]
                  ['childs'][ strtolower($child2['name']) ] = $child2;

                if (strtolower($child2['name'])==$classCalled) {
                  $this->access = $child2;
                }
              }
            }
          }
        }
      }
      // set access button
      foreach( $this->access['options'] as $key=>$value) {
        $this->access['options'][$key] = (is_string($value)) ? (boolean) $value : $value;
      }
      return (!empty($result)) ? $result : [];
    }
  }
}