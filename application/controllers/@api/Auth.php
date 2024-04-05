<?php

/**
 * Class Auth
 *
 * This class handles authentication-related operations.
 */
class Auth extends BaseApi
{
  /**
   * This variable determines whether the system is in secure mode or not.
   * Set it to TRUE for secure mode, and FALSE for non-secure mode.
   */
  public $secure = FALSE;

  /**
   * This array contains a list of forbidden actions.
   * These actions should not be accessible.
   */
  public $forbidden = [ 
    'list_REST',
    'show_REST',
    'create_REST',
    'update_REST',
    'delete_REST',
    'paginate_REST',
    'entries_REST',
  ];

  /**
   * Perform user login and generate a JWT token.
   *
   * @Request: POST
   */
  public function login_POST()
  {
    // Check if a user with the provided name exists
    if ($this->models->where('name', $this->method('name'))->count_rows()) {
      $data = $this->models->where('name', $this->method('name'))->get();
      if (password_verify($this->method('code'), $data->code)) {
        $token = $this->generateJwt($data, 25200); // 7 hari
        
        $this->data['name'] = $data->name;
        $this->data['serial'] = $data->serial;
        $this->data['token'] = $token['token'];
        $this->data['expired'] = $token['expired'];
        $this->data['state'] = 'VERIFIED';

        $this->return(200, "Success");
      }else {
        $this->return(400, "Wrong Code");
      }
    }else {
      $this->return(400, "Name Not Found");
    }
  }

  public function verified_POST() {
    $this->load->library('session');
    if ($this->session->has_userdata('AUTHENTICATION')) {
      $this->return(200, "Already Set");
    }else {
      $this->session->set_userdata($this->method);
      $this->return(200, "Success Set Session");
    }
  }

  public function logout_GET()
  {
    $this->load->library('session');
    session_destroy();
    $this->session->unset_userdata('AUTHENTICATION');
    $this->return(200, "Success Unset Session");
  }

  /**
   * Perform user login and generate a JWT token.
   *
   * @Request: GET
   */
  public function token_GET()
  {
    $this->data = $this->generateJwt([]);
    $this->return(200, "Success");
  }
}