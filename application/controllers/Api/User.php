<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BaseApi
{
  
  # create data
  public function create_REST()
  {
    $QUERY = $this->query;

    $data = $this->method;
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    if (!empty($data['pin'])) {
      $data['pin'] = password_hash($data['pin'], PASSWORD_DEFAULT);
    }
    
    $QUERY['data'] = $data;

    $check = $this->models->get(['where'=>['email'=>$data['email']]])->result();
    if (isset($check[0])) {
      $this->return(404, "User Already Exists");
    }else {
      $this->data = $this->models->set( $QUERY );
      $this->return(200, "User Created");
    }
  }

  # update data
  public function update_REST()
  {
    $QUERY = $this->query;
    $QUERY['where'] = ['id_user'=> $this->id];

    $data = $this->method;
    if (isset($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    if (isset($data['password'])) {
      $data['pin'] = password_hash($data['pin'], PASSWORD_DEFAULT);
    }

    $QUERY['data'] = $data;

    $this->data = $this->models->put( $QUERY );
    $this->return(200, "User Created");
  }


  

}