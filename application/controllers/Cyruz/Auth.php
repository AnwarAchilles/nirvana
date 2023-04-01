<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth extends CyruzController
{
  
  public $auth = TRUE;

  # main
  public function __construct()
  {
    parent::__construct();

    $this->data['layout']['draw'] = FALSE;
  }
  
  # index page
  public function index()
  {
    $this->login();
  }

  # todo login user
  public function login()
  {
    $this->data['layout']['source'] = [ 'Cyruz', 'Auth', 'login' ];
    $this->data['layout']['title'] = 'Authentications Login';

    $this->layout( $this->data );
  }

  # todo register user
  public function register()
  {
    $this->data['layout']['source'] = [ 'Cyruz', 'Auth', 'register' ];
    $this->data['layout']['title'] = 'Authentications Register';

    $this->layout( $this->data );
  }

  # todo logout user
  public function logout()
  {
    session_unset();
    session_destroy();
    redirect(base_url('Cyruz/Auth'));
  }
}