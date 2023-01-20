<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends BaseApi
{
  

  public function login_POST()
  {
    if (isset($this->method['email'])) {
      $user = $this->models->get(['where'=> ['email'=>$this->method['email']] ])->row_array();
      $session = $this->controller->session;
  
      if ($user) {
        if (password_verify($this->method['password'], $user['password'])) {
          $this->data['id_user'] = $user['id_user'];
          $this->data['email'] = $user['email'];
          @ $session->set_userdata( ['credentials'=> $this->data] );
          $this->return(200, "user-verified");
        }else {
          $this->return(200, "password-wrong");
        }
      }else {
        $this->return(200, "user-not-found");
      }
    }else {
      $this->return(200, "user-not-found");
    }
  }
  

}