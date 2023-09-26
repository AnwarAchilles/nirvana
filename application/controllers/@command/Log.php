<?php



class Log {

  private $Entries = [];

  public function index() {
    $this->list();
  }

  public function list() {
    $this->_load();

    echo "\n";
    foreach ($this->Entries as $name=>$row) {
      echo "﹒".$name;
      echo " \t………… ".$row['size'];
      echo "\n";
    }
  }

  public function show( $date ) {
    $this->_load();
    if (isset($this->Entries[$date]['name'])){
      echo $this->Entries[$date]['name'];
      echo file_get_contents($this->Entries[$date]['path']);
    }else {
      echo "Logs ".$date." - Not found";
    }
  }

  public function remove( $date ) {
    $Target = "logs-".$date.".php";
    if (file_exists($Target)) {
      unlink($Target);
    }
  }

  public function clear() {
    $Target = glob(PATH_ARCHIVE.'/logs/*');
    foreach ($Target as $row) {
      unlink($row);
    }
  }

  private function _load() {
    $Target = glob(PATH_ARCHIVE.'/logs/*');

    foreach ($Target as $row) {
      $name = str_replace('log-', '', pathinfo($row, PATHINFO_FILENAME));
      $this->Entries[ $name ] = [
        'name'=> date_format(date_create($name), 'd/m/Y'),
        'path'=> $row,
        'size'=> formatBytes(filesize($row)),
      ];
    }
  }

}