<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends BaseApi
{
  
  /* ---- ---- ---- ----
   * REST CREATE
   * ---- ---- ---- ---- */
  public function create_REST()
  {
    $QUERY = $this->method;
    $QUERY['datetime'] = date('Y-m-d H:i:s');
    // set name & prefix
    $QUERY['prefix'] = strtolower($this->method('prefix'));
    $QUERY['status'] = strtoupper($this->method('status'));
    // set seource
    $source = [];
    $source['remote_addr'] = $_SERVER['REMOTE_ADDR'];
    $source['remote_port'] = $_SERVER['REMOTE_PORT'];
    $source['current_url'] = current_url();
    $source['current_date'] = date('Y-m-d H:i:s');
    $source['server_signature'] = $_SERVER['SERVER_SIGNATURE'];
    $source['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $source['redirect_url'] = $_SERVER['REDIRECT_URL'];
    $QUERY['source'] = json_encode( $source );
    // set data menu
    $menu = $this->controller->api('GET', 'menu/'.$this->method['id_menu']);
    $QUERY['menu'] = json_encode( $menu );
    // set data user
    $user = $this->controller->api('GET', 'user/'.$this->method['id_user']);
    $QUERY['user'] = json_encode( $user );
    // create to db
    $this->data['id'] = $this->models->set(['data'=> $QUERY]);
    $this->data = $QUERY;
    $this->return(200);
  }
  

}