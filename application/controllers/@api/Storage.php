<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Storage
 *
 * This class represents a custom API controller that extends CoreApi.
 *
 * @class Storage
 * @extends CoreApi
 */
class Storage extends CoreApi
{

  /**
   * Constructor for the Storage class.
   * 
   * You can perform additional setup and logic here.
   */
  public function __construct() {
    parent::__construct();

    $this->load->library('upload');

    // Add your initialization code here
  }

  private function upload_file( $file ) {
    if ($this->file['type'] == 'image/png') {
      $this->upload_file_image( $file );
    }
  }

  private function upload_file_image( $file ) {

  }


  public function upload_POST() {
    foreach ($this->files as $name=>$file) {
      if (is_file($file['tmp_name'])) {
        $this->upload_file( $file );
      }
    }

    $this->return(200, "Success Upload to storage");
  }

  public function delete_POST( $uuid ) {
    
  }

}