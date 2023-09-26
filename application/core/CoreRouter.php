<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class CoreRouter
 *
 * Custom Router class extending CI_Router.
 * It handles routing for API requests and CLI commands.
 */
class CoreRouter extends CI_Router {
  private $api = 'BaseApi';
  private $run = 'Baserun';

  /**
   * Constructor for CoreRouter.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Override of CI_Router's _set_routing method.
   */
  protected function _set_routing() {
    parent::_set_routing();
  }

  /**
   * Override of CI_Router's _validate_request method.
   *
   * @param array $segments The URI segments to validate.
   * @return array The validated URI segments.
   */
  protected function _validate_request($segments) {
    if (count($segments) === 0) {
      return $segments;
    }

    // Handle API requests
    if ($segments[0] == 'api') {
      array_shift($segments);
      if (isset($segments[0])) {
        $segments[0] = ucfirst($segments[0]);
      } else {
        $segments[0] = $this->api;
      }
      
      if (file_exists(PATH_APPLICATION.'controllers/@api/'.$segments[0].'/')) {
        $this->set_directory('@api/'.$segments[0]);
        if (file_exists(PATH_ROOT.'application/controllers/@api/'.$segments[0].'/'.ucfirst($segments[1]).'.php')) {
          array_shift($segments);
          $segments[0] = ucfirst($segments[0]);
          return $segments;
        } else {
          array_shift($segments);
          $segments[0] = $this->api;
          return $segments;
        }
      }else {
        $this->set_directory('@api');
        if (file_exists(PATH_ROOT.'application/controllers/@api/'.ucfirst($segments[0]).'.php')) {
          return $segments;
        } else {
          $segments[0] = $this->api;
          return $segments;
        }
      }
    }

    // Handle CLI commands
    if ($segments[0] == 'command') {
      if (is_cli()) {
        array_shift($segments);
        $segments[0] = ucfirst($segments[0]);
        $this->set_directory('@command');
        return $segments;
      }
    }

    return parent::_validate_request($segments);
  }
}
