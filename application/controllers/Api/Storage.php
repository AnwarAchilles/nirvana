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


  public function upload_POST( $filename )
  {
    $extension = pathinfo( $this->file[$filename]['name'], PATHINFO_EXTENSION);
    $location = str_replace('/index.php', '/storage/', $_SERVER['SCRIPT_FILENAME']);

    if (! file_exists($location.'/'.$extension)) {
      mkdir($location.'/'.$extension);
    }

    // $this->load->library('upload', [
    //   'upload_path'=> ,
    // ]);

    // if ( $this->upload->do_upload( $filename ) ) {

    // }

    $this->data = $this->file;
    $this->return(200);
  }

  // bulk upload by entries files
  public function upload_entries_POST()
  {
    $this->load->library('upload', [
      'upload_path'=> str_replace('/index.php', '', $_SERVER['SCRIPT_FILENAME']),
    ]);

    $this->data = $this->file;
    $this->return(200);
  }


  

}