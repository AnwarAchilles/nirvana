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
class Storage extends BaseApi
{

  /**
   * This variable determines whether the system is in secure mode or not.
   * Set it to TRUE for secure mode, and FALSE for non-secure mode.
   */
  public $secure = FALSE;

  /**
   * This array contains a list of forbidden actions.
   * These actions should not be accessible.
   */
  public $forbidden = [ 
    'list_REST',
    'show_REST',
  ];


  /**
   * Constructor for the Storage class.
   * 
   * You can perform additional setup and logic here.
   */
  public function __construct() {
    parent::__construct();

    $this->load->library('upload');
    $this->load->library('image_lib');

    // Add your initialization code here
  }

  /**
   * Create and upload a file to storage.
   *
   * This function creates and uploads a file to the storage directory. It first
   * checks if the storage directory exists; if not, it creates it. Then, it generates
   * a unique serial number for the file. The file is uploaded with the serial number
   * as its name.
   *
   * @return void
   */
  public function create_REST() {
    // Define the directory for storage.
    $directory = PATH_STORAGE;

    // Check if the storage directory exists; if not, create it.
    if (!file_exists($directory)) {
      mkdir($directory, 0777, TRUE);
    }

    // Generate a unique serial number for the file.
    $serial = $this->serialNumber();

    // Configuration for file upload.
    $config['upload_path'] = $directory;
    $config['allowed_types'] = '*';
    $config['file_name'] = $serial;

    // Merge additional configuration from the request method.
    $config = array_merge($config, $this->method);

    // Iterate through uploaded files.
    foreach ($this->files as $name => $file) {
      if (is_file($file['tmp_name'])) {
        // Initialize the upload library with the configuration.
        $this->upload->initialize($config);
        
        // Perform the file upload.
        $this->upload->do_upload($name);
      }
    }

    // Get data about the uploaded file.
    $this->data = $this->upload->data();

    // Return a success response.
    $this->return(200, "Success Upload to storage");
  }


  /**
   * Delete a file from storage.
   *
   * This function attempts to delete a file specified by its filename from storage.
   * If the file exists, it is deleted, and a success message is returned.
   * If the file doesn't exist, a failure message is returned.
   *
   * @return void
   */
  public function delete_REST() {
    // Get the filename from the request or data source (e.g., $this->id).
    $filename = $this->id;

    // Check if the file exists in storage.
    if (file_exists(storage($filename, true))) {
      // If the file exists, unlink (delete) it.
      unlink(storage($filename, true));
      
      // Return a success response.
      $this->return(200, 'Success Discard from storage');
    } else {
      // If the file doesn't exist, return a failure response.
      $this->return(400, 'Failed Discard file not exists');
    }
  }


  /**
   * Resize image and change its quality.
   *
   * This function resizes an image specified by its filename to a new quality level.
   *
   * @return void
   */
  public function resize_POST() {
    // Get the filename from the request.
    $filename = $this->method('filename');

    // Get the original width and height of the image.
    list($width, $height) = getimagesize(storage($filename, true));

    // Configuration for image resizing.
    $config['image_library'] = 'gd2';
    $config['source_image'] = storage($filename, true);
    $config['new_image'] = storage($filename, true);

    // Set the quality for the resized image.
    $config['quality'] = $this->method('quality');

    // Calculate the new width and height based on the quality.
    $config['width'] = ceil($width * percent2decimal((int) $this->method('quality')));
    $config['height'] = ceil($height * percent2decimal((int) $this->method('quality')));
    
    // Initialize the image library with the configuration.
    $this->image_lib->initialize($config);

    // Resize the image and check if it was successful.
    if ($this->image_lib->resize()) {
      // Set data for response.
      $this->data['quality'] = (int) $this->method('quality');
      $this->data['width'] = $width;
      $this->data['height'] = $height;
      
      // Return a success response.
      $this->return(200, 'Success Resize to new quality');
    }
  }


}