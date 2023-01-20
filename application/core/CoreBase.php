<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# SET CORE LOADER
class CoreBase extends CI_Controller
{

  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
	public function __construct()
  {
    parent::__construct();
    
    $this->config->load('core');
  }

  public function api()
  {
    foreach ($this->config->item('loader')['api'] as $base) {
      require_once APPPATH."/controllers/Api/" . $base . ".php";
    }
  }
  public function controller()
  {
    foreach ($this->config->item('loader')['controller'] as $base) {
      require_once APPPATH."/controllers/" . $base . ".php";
    }
  }
  public function model()
  {
    foreach ($this->config->item('loader')['model'] as $base) {
      require_once APPPATH."/models/" . $base . ".php";
    }
  }


}