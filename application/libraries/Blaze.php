<?php



class Blaze {
  
  public $Data = [];

  public function createFile( $blaze, $target ) {
    $source = $this->load( $blaze );
    foreach ($this->Data as $key=>$data) {
      $source = str_replace("@{ $key }", $data, $source);
    }
    file_put_contents(PATH_APPLICATION.$target, $source);
    return $this;
  }


  public function createFolder( $target ) {
    if (!file_exists(PATH_APPLICATION.$target)) {
      mkdir(PATH_APPLICATION.$target, 777, TRUE);
    }
    return $this;
  }


  public function removeFile( $source ) {
    if (file_exists(PATH_APPLICATION.$source)) {
      unlink(PATH_APPLICATION.$source);
    }
    return $this;
  }


  public function removeFolder( $source ) {
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


  public function data( $variable, $data='' )
  {
    if (!empty($data)) {
      $this->Data[$variable] = $data;
    }else {
      return $this->Data[$variable];
    }
  }


  public function load( $source )
  {
    if (file_exists(PATH_RESOURCE.'/blaze/'.$source.'.blaze')) {
      return file_get_contents(PATH_RESOURCE.'/blaze/'.$source.'.blaze');
    }else {
      return '';
    }
  }


}