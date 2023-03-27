<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends BaseApi
{
  
  public function compress_POST( $size )
  {
    $this->load->library('upload', [
      // upload config
    ]);
    $this->load->library('image_lib', [
      // image config
    ]);


    $this->data = $this->files;
    $this->return(200);
  }


  

}