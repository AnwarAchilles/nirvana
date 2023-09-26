<?php



class Api {

  private $Entries = [];

  private $Version = [];

  private $Base = [ 'paginate', 'entries', 'costum' ];

  private $Method = ['GET', 'POST', 'PUT', 'DELETE'];



  public function index() {
    $this->list();
  }

  public function list( $version='' ) {
    $this->_load_version();
    $this->_load_entries( $version );

    if ($version=='') {
echo "\n";
echo "Version : \n";
      foreach ($this->Version as $name) {
echo "﹒".$name."\n ---- ---- ----";
      }
    }

echo "\n";
    foreach ($this->Entries as $class=>$row) {
echo "﹒".$class." (".count($row['rest'])+count($row['base'])+count($row['user']).")  \t\t…………";
echo " nest:".$row['nest']."";
echo " rest:".count($row['rest'])."";
echo " base:".count($row['base'])."";
echo " user:".count($row['user'])."";
echo "\n";
    }
  }


  public function show( $className, $version='' ) {
    $this->_load_entries( $version );

    if (isset($this->Entries[$className])) {
      $totalRest = count($this->Entries[$className]['rest']);
      $totalBase = count($this->Entries[$className]['base']);
      $totalUser = count($this->Entries[$className]['user']);
      $total = $totalRest + $totalBase + $totalUser;

echo "\n".$className." Endpoint \t---- ---- ---- ---- $total \n";
      $version = ($version!=='') ? $version.'/' : '';
echo "".base_url('api/'.$version.strtolower($className))."\n";
  
echo "\n﹟Rest ---- $totalRest \n";
    foreach ($this->Entries[$className]['rest'] as $name=>$method) {
echo "  ﹒".$name." \t".implode(', ', $method)."\n";
    }
  
echo "\n﹟Base ---- $totalBase \n";
    foreach ($this->Entries[$className]['base'] as $name=>$method) {
echo "  ﹒".$name." \t".implode(', ', $method)."\n";
    }
  
echo "\n﹟User ---- $totalUser \n";
    foreach ($this->Entries[$className]['user'] as $name=>$method) {
echo "  ﹒".$name." \t".implode(', ', $method)."\n";
      }
    }else {
      echo "\nApi ".$className." -- Not Found\n";
    }

  }


  private function _load_entries( $version='' ) {
    $Apis = glob(PATH_APPLICATION.'/controllers/@api/'.$version.'/*');
    foreach ($Apis as $file) {
      if (is_file($file)) {
        $Data = [ 'nest'=>'ready', 'rest'=> [], 'base'=> [], 'user'=> [], ];
        $ClassName = pathinfo($file, PATHINFO_FILENAME);
        require_once $file;
        foreach (get_class_methods($ClassName) as $method) {
          
          if (str_contains($method, 'REST')) {
            $name = str_replace('_REST', '', $method);
            $Data['rest'][$name] = [];
          }
          
          foreach ($this->Method as $pattern) {
            if (str_contains($method, $pattern)) {
              $name = str_replace('_'.$pattern, '', $method);
              $method = str_replace('_', '', strchr($method, '_'));
  
              foreach ($this->Base as $pattern) {
                if ($name == $pattern) {
                  $Data['base'][$name][] = $method;
                }
              }
              
              if (!isset($Data['rest'][$name])) {
                if (!isset($Data['base'][$name])) {
                  if ($name!=='index') {
                    $Data['user'][$name][] = $method;
                  }
                }
              }else {
                $Data['rest'][$name][] = $method;
              }
            }
          }
  
        }
        
        $this->Entries[$ClassName] = $Data;
      }
    }


    $Models = glob(PATH_APPLICATION.'/models/'.$version.'/*');
    foreach ($Models as $model) {
      if (is_file($model)) {
        $ClassName = str_replace('_', '',pathinfo($model, PATHINFO_FILENAME));
        if (!isset($this->Entries[$ClassName])) {
          if ($ClassName!=='BaseModel') {
            $this->Entries[$ClassName] = $this->Entries['BaseApi'];
            $this->Entries[$ClassName]['nest'] = 'not-ready';
          }
        }
      }
    }

    
    unset($this->Entries['BaseApi']);
    unset($this->Entries['BaseModel']);
  }

  public function _load_version() {
    $Apis = glob(PATH_APPLICATION.'/controllers/@api/*');
    foreach ($Apis as $file) {
      if (is_dir($file)) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $this->Version[] = $name;
      }
    }
  }

}