<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends BaseApi
{
  
  public function resize_POST( $size )
  {
    $this->controller->load->library('image_lib', [
      'image_library'=> 'gd2',
      'source_image'=> $this->method['full_path'],
      'new_image'=> $this->method['full_path'],
      'width'=> ($this->method['image_width'] * $size),
      'height'=> ($this->method['image_height'] * $size),
    ]);

    // unlink($this->method['full_path']);

    if ( $this->controller->image_lib->resize() ) {
      $this->data = $this->method;
      $this->return(200);
    }else {
      $this->data = $this->method;
      $this->return(404);
    }
  }


  public function upload_POST( $filename )
  {
    $extension = pathinfo( $this->file[$filename]['name'], PATHINFO_EXTENSION);
    $location = FCPATH.'storage\\';
    
    if (! file_exists($location.'\\'.$extension)) {
      mkdir($location.'\\'.$extension);
    }
    $location = $location.'\\'.$extension.'\\';

    $this->controller->load->library('upload', [
      'upload_path'=> $location,
      'allowed_types'=> '*',
      'encrypt_name'=> true,
      'file_ext_tolower'=> true,
    ]);

    if ( $this->controller->upload->do_upload( $filename ) ) {
      $this->data = $this->controller->upload->data();
      $this->return(200);
    }else {
      $this->data = $this->controller->upload->display_errors();
      $this->return(400);
    }
  }


  

}