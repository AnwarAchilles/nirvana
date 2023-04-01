<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage_menu extends BaseApi
{

  public function linked_GET()
  {
    $QUERY = $this->query;

    $QUERY['select'] = 'menu.name';
    $QUERY['join'] = [
      'storage'=> 'storage_menu.id_storage = storage.id_storage',
      'menu'=> 'storage_menu.id_menu = menu.id_menu',
    ];
    $QUERY['where'] = [
      'storage_menu.id_storage'=> $this->id, 
    ];

    $this->data = $this->models->get( $QUERY )->result_array();
    
    $this->return(200);
  }

  /* ---- ---- ---- ----
   * REST CREATE
   * ---- ---- ---- ---- */
  public function create_REST()
  {
    $QUERY = $this->query;
    $QUERY['data'] = $this->method;

    $where = [];
    $where['id_storage'] = $this->method('id_storage');
    $where['id_menu'] = $this->method('id_menu');
    if (isset($this->method['id_index'])) {
      $where['id_index'] = $this->method('id_index');
    }

    $check = $this->models->get([
      'where'=> $where
    ]);
    if (!$check) {
      $this->data['id'] = $this->models->set( $QUERY );
      $this->return(200);
    }else {
      $this->return(203, 'Data Already Exists');
    }
  }

}