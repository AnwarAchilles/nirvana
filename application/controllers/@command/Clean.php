<?php

class Clean extends CoreController
{

  public function all() {
    $this->cache();
    $this->session();
    $this->cookie();
    $this->log();
  }

  public function cache() {
    $Clean = glob(PATH_ARCHIVE.'/caches/*');
    foreach ($Clean as $row) {
      if (!str_contains($row, 'index')) {
        unlink($row);
      }
    }
    $Clean = glob(PATH_ARCHIVE.'/twigs/*');
    foreach ($Clean as $row) {
      $Cleann = glob($row.'/*');
      foreach ($Cleann as $row) {
        if (!str_contains($row, 'index')) {
          unlink($row);
        }
      }
      if (is_dir($row)) {
        rmdir($row);
      }
    }
echo "﹒Caches cleared!\n";
  }

  public function session() {
    $Clean = glob(PATH_ARCHIVE.'/sessions/*');
    foreach ($Clean as $row) {
      if (!str_contains($row, 'index')) {
        unlink($row);
      }
    }
echo "﹒Sessions cleared!\n";
  }

  public function cookie() {
    $Clean = glob(PATH_ARCHIVE.'/cookies/*');
    foreach ($Clean as $row) {
      if (!str_contains($row, 'index')) {
        unlink($row);
      }
    }
echo "﹒Cookies cleared!\n";
  }

  public function log() {
    $Clean = glob(PATH_ARCHIVE.'/logs/*');
    foreach ($Clean as $row) {
      if (!str_contains($row, 'index')) {
        unlink($row);
      }
    }
echo "﹒Logs cleared!\n";
  }

}