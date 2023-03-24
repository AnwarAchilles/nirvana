<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class CyruzController extends CoreController
{
  public $auth = FALSE;
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
  public function __construct() {
    parent::__construct();
    // do something here
    if ( ($this->auth) && ($this->uri->segment(3)!=='logout') ) {
      $this->credentials_check("/cyruz", TRUE);
    }else {
      $this->credentials_check("/cyruz/auth");
    }
    // set credentials login
    $this->data['users'] = [];
    $this->data['users'] = $this->session->userdata("credentials");
    // set sidebars
    $this->data['sidebars'] = $this->sidebars();
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
          $this->data['access'] = $sidebar;
        }

        if (isset($sidebar['childs'])) {
          $result[ strtolower($sidebar['name']) ]['childs'] = [];
          foreach ( $sidebar['childs'] as $child ) {
            $result[ strtolower($sidebar['name']) ]['childs'][ strtolower($child['name']) ] = $child;

            if (strtolower($child['name'])==$classCalled) {
              $this->data['access'] = $child;
            }
          }
        }
      }
      return (!empty($result)) ? $result : [];
    }
  }
}