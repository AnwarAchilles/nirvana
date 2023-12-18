<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The CoreController class is responsible for handling the core functionality of the application.
 */
require_once PATH_APPLICATION.'/core/CoreApi.php';
require_once PATH_APPLICATION."/core/CoreBase.php";

# SET BASE API & CONTROLLER
$BASE = new CoreBase();
$BASE->api();
$BASE->controller();
$BASE->model();

/**
 * The CoreController class extends the CI_Controller class and serves as the base controller for the application.
 */
class CoreController extends CI_Controller
{

  public $useSession = FALSE;

  /**
   * Constructor for the class.
   *
   * @return void
   */
  public function __construct() {
    // Set CodeIgniter
    parent::__construct();

    // Load config
    $this->config->load('core');
    $this->config->controller = $this->config->item('controller');
    $this->config->loader = $this->config->item('loader');

    // Load libraries
    foreach ($this->config->loader['libraries'] as $key => $value) {
      if ($value=='session') {
        if ($this->useSession==TRUE) {
          if (!is_numeric($key)) {
            $this->load->library($value, $key);
          } else {
            $this->load->library($value);
          }  
        }
      }else {
        if (!is_numeric($key)) {
          $this->load->library($value, $key);
        } else {
          $this->load->library($value);
        }
      }
    }

    // Load helpers
    foreach ($this->config->loader['helper'] as $value) {
        $this->load->helper($value);
    }

    if (isset($this->layout)) {
      // Set layout configuration
      if (file_exists(PATH_APPLICATION . "/config/layout/" . strtolower(get_class($this)) . ".php")) {
          $this->layout->config(strtolower(get_class($this)));
      } else {
          $this->layout->config('default');
      }
      // set default stylesheet & javascript
      $this->initStylesheet();
      $this->initJavascript();
    }

  }

  private function initStylesheet() {
    
  }

  private function initJavascript() {
    $this->layout->script('base_url', base_url());
    $this->layout->script('current_url', current_url());
    $this->layout->script('resource', resource());
    $this->layout->script('archive', archive());
    $this->layout->script('storage', storage());
  }

}