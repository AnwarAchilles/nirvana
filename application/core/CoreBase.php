<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class CoreBase
 *
 * This class serves as the base controller for setting up core components in your application.
 * It loads API controllers, regular controllers, models, and Eloquent.
 */
class CoreBase extends CI_Controller
{

  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
	public function __construct()
  {
    parent::__construct();
    
    // Load the core configuration
    $this->config->load('core');
  }

  /**
   * Load API Controllers
   */
  public function api()
  {
    foreach ($this->config->item('loader')['api'] as $base) {
      require_once PATH_APPLICATION."/controllers/@api/" . $base . ".php";
    }
  }

  /**
   * Load Regular Controllers
   */
  public function controller()
  {
    foreach ($this->config->item('loader')['controller'] as $base) {
      require_once PATH_APPLICATION."/controllers/" . $base . ".php";
    }
  }

  /**
   * Load Models
   */
  public function model()
  {
    // Include the core Model and CoreModel
    require_once PATH_ROOT."/system/core/Model.php";
    require_once PATH_APPLICATION."/core/CoreModel.php";

    // Load application-specific models
    foreach ($this->config->item('loader')['model'] as $base) {
      require_once PATH_APPLICATION."/models/_" . $base . ".php";
    }
  }

  /**
   * Load Eloquent ORM
   */
  public function eloquent()
  {
    require_once PATH_APPLICATION."/core/Eloquent/".$this->config->item('loader')['eloquent']."Eloquent.php";
  }
}
