<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth extends CyruzController
{
  
  public $auth = TRUE;
  
  // index page
  public function index()
  {
    $this->login();
  }

  // todo login user
  public function login()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Auth', 'login' ],
      'title'=> 'Authentications Login',
    ];

    $this->layout( $this->data );
  }

  // todo register user
  public function register()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> FALSE,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Auth', 'register' ],
      'title'=> 'Authentications Register',
    ];

    $this->layout( $this->data );
  }

  // todo logout user
  public function logout()
  {
    session_unset();
    session_destroy();
    redirect(base_url('cyruz/auth'));
  }
}