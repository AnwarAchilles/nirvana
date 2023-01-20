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
   * SIDEBARS PER USER
   * ---- ---- ---- ---- */
  public function sidebars()
  {
    if (!empty($this->data['users'])) {
      // load user
      $user = $this->api("GET", "user/".$this->data['users']['id_user']);
      // load menu sidebar
      $sidebars = $this->api("GET", "role_menu/sidebar/".$user['id_role']);
      // output data
      $residebars = [];
      foreach( $sidebars as $sidebar ) {
        $sidebar['options'] = json_decode($sidebar['options'], true);
        $residebars[ strtolower($sidebar['name']) ] = $sidebar;
        if (isset($sidebar['child'])) {
          $residebars[ strtolower($sidebar['name']) ]['child'] = [];
          foreach ( $sidebar['child'] as $child ) {
            $child['options'] = json_decode($child['options'], true);
            $residebars[ strtolower($sidebar['name']) ]['child'][ strtolower($child['name']) ] = $child;
          }
        }
      }
      return (!empty($residebars)) ? $residebars : [];
    }
  }
}