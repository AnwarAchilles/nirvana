<?php


class Blaze extends CI_Controller {

  private $Data = [];

  /**
   * Process and set different variations of a filename into the data.
   *
   * This function takes a filename as input and sets its capital, lower, and upper
   * case variations into the data for later use.
   *
   * @param array $data An associative array containing the 'filename' key.
   */
  private function main( $data )
  {
    // Set the capital case variation of the filename.
    $this->data('name-capital', ucfirst($data['filename']));
    
    // Set the lower case variation of the filename.
    $this->data('name-lower', strtolower($data['filename']));
    
    // Set the upper case variation of the filename.
    $this->data('name-upper', strtoupper($data['filename']));
  }

  /**
   * Method Name: create
   *
   * Description: Creates files and folders based on a provided command.
   *
   * @param string $command The command specifying the state and filename.
   */
  public function create($command) {
    // Load configuration
    $this->load->config('blaze');

    // Extract state and filename from the command
    $state = explode(':', $command)[0];
    $filename = explode(':', $command)[1];
    $repositor = '';

    // Check if filename contains a dot (indicating a repository structure)
    if (strpos($filename, '.')) {
      $list = explode('.', $filename);
      $filename = array_pop($list);
      $repositor = implode('/', $list);
    }

    // Call the main method with filename and repository information
    $this->main([
      'filename' => $filename,
      'repositor' => $repositor,
    ]);

    // Get configuration based on the state
    $config = $this->config->item('blaze')[$state];

    // Loop through configuration and create files and folders
    foreach ($config as $row) {
      $blaze = $row[0];

      $folder = $row[2];
      $folder = str_replace('@{filename}', $filename, $folder);
      $folder = str_replace('@{repositor}', $repositor, $folder);

      $file = $row[1];
      $file = str_replace('@{filename}', $filename, $file);
      $file = str_replace('@{repositor}', $repositor, $file);

      $targetFolder = str_replace('//', '/', $folder);
      $targetFile = str_replace('//', '/', $folder . '/' . $file);

      // Call methods to create folder and file
      $this->createFolder($targetFolder);
      $this->createFile($blaze, $targetFile);
    }

    echo 'Success creating ' . $state . ' files.';
  }


  /**
   * Method Name: remove
   *
   * Description: Removes files based on a provided command.
   *
   * @param string $command The command specifying the state and filename.
   */
  public function remove($command) {
    // Load configuration
    $this->load->config('blaze');

    // Extract state and filename from the command
    $state = explode(':', $command)[0];
    $filename = explode(':', $command)[1];
    $repositor = '';

    // Check if filename contains a dot (indicating a repository structure)
    if (strpos($filename, '.')) {
      $list = explode('.', $filename);
      $filename = array_pop($list);
      $repositor = implode('/', $list);
    }

    // Get configuration based on the state
    $config = $this->config->item('blaze')[$state];

    // Loop through configuration and remove files
    foreach ($config as $row) {
      $blaze = $row[0];

      $folder = $row[2];
      $folder = str_replace('@{filename}', $filename, $folder);
      $folder = str_replace('@{repositor}', $repositor, $folder);

      $file = $row[1];
      $file = str_replace('@{filename}', $filename, $file);
      $file = str_replace('@{repositor}', $repositor, $file);

      $targetFile = str_replace('//', '/', $folder . '/' . $file);

      // Call method to remove the file
      $this->removeFile($targetFile);
    }

    echo 'Success removing ' . $state . ' files.';
  }


  /**
   * Method Name: bundle
   *
   * Description: Creates files and folders based on a filename for multiple states.
   *
   * @param string $filename The filename to be used for creating files and folders.
   */
  public function bundle($filename) {
    // Load configuration
    $this->load->config('blaze');

    // Get bundle configuration
    $config = $this->config->item('blaze_bundle');

    // Loop through bundle states and create files and folders
    foreach ($config as $state) {
      // Formulate the command for creating based on state and filename
      $command = $state . ':' . $filename;

      // Call the create method with the command
      $this->create($command);
    }
  }


  






  private function createFile( $blaze, $target ) {
    $source = $this->load( $blaze );
    foreach ($this->Data as $key=>$data) {
      $source = str_replace("@{ $key }", $data, $source);
    }
    file_put_contents(PATH_APPLICATION.$target, $source);
    return $this;
  }

  private function createFolder( $target ) {
    if (!file_exists(PATH_APPLICATION.$target)) {
      mkdir(PATH_APPLICATION.$target, 777, TRUE);
    }
    return $this;
  }

  private function removeFile( $source ) {
    if (file_exists(PATH_APPLICATION.$source)) {
      unlink(PATH_APPLICATION.$source);
    }
    return $this;
  }

  private function removeFolder( $source ) {
    if (file_exists(PATH_APPLICATION.$source)) {
      if (count(glob(PATH_APPLICATION.$source.'/*')) > 0) {
        foreach (glob(PATH_APPLICATION.$source.'/*') as $file) {
          unlink($file);
        }
      }
      if (count(glob(PATH_APPLICATION.$source.'/*')) == 0) {
        rmdir(PATH_APPLICATION.$source);
      }
    }
    return $this;
  }


  private function data( $variable, $data='' )
  {
    if (!empty($data)) {
      $this->Data[$variable] = $data;
    }else {
      return $this->Data[$variable];
    }
  }


  private function load( $source )
  {
    if (file_exists(PATH_RESOURCE.'/blaze/'.$source.'.blaze')) {
      return file_get_contents(PATH_RESOURCE.'/blaze/'.$source.'.blaze');
    }else {
      return '';
    }
  }


}