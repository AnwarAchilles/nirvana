<?php

class Base extends CoreController
{
  public function index() {
    echo " _   _ _                             
| \ | (_)_ ____   ____ _ _ __   __ _ 
|  \| | | '__\ \ / / _` | '_ \ / _` |	Codeigniter 3.1.13 - Improved
| |\  | | |   \ V / (_| | | | | (_| |	@anwarachilles
|_| \_|_|_|    \_/ \__,_|_| |_|\__,_| \n\n";
    echo '
✔ Development Server
  $ php nirvana serve

✔ Migrate and Seeder
  $ php nirvana database migrate
  $ php nirvana database rollback
  $ php nirvana database migration [table]
  $ php nirvana database seed [table]

✔ Api Endpoint List
  $ php nirvana api list [version]
  $ php nirvana api show [nontroller] [version]
  
✔ Logs Management & Monitoring
  $ php nirvana log list
  $ php nirvana log show [Date]
  $ php nirvana log remove [Date]
  $ php nirvana log clear

✔ Blaze generator template
  $ php nirvana blaze create [config]:[name]
  $ php nirvana blaze remove [config]:[name]
  $ php nirvana blaze bundle [name]

✔ Extra for cleaning caches, cookies
  $ php nirvana base clean all
  $ php nirvana base clean [cache|session|cookie|log]

✔ Custom cli runner on controller/@run
  $ php nirvana [controller] [method] [parameter]

    ';
  }

  public function clean( $list='' ) {
    if (strpos($list, 'cache')!==false || strpos($list, 'all')!==false) {
      $Clean = glob(PATH_ARCHIVE.'/caches/*');
      foreach ($Clean as $row) {
        unlink($row);
      }
      $Clean = glob(PATH_ARCHIVE.'/twigs/*');
      foreach ($Clean as $row) {
        $Cleann = glob($row.'/*');
        foreach ($Cleann as $row) {
          unlink($row);
        }
        if (is_dir($row)) {
          rmdir($row);
        }
      }
echo "﹒Caches cleared!\n";
    }
    if ( strpos($list, 'session')!==false || strpos($list, 'all')!==false) {
      $Clean = glob(PATH_ARCHIVE.'/sessions/*');
      foreach ($Clean as $row) {
        unlink($row);
      }
echo "﹒Sessions cleared!\n";
    }
    if (strpos($list, 'cookie')!==false || strpos($list, 'all')!==false) {
      $Clean = glob(PATH_ARCHIVE.'/cookies/*');
      foreach ($Clean as $row) {
        unlink($row);
      }
echo "﹒Cookies cleared!\n";
    }
    if (strpos($list, 'log')!==false || strpos($list, 'all')!==false) {
      $Clean = glob(PATH_ARCHIVE.'/logs/*');
      foreach ($Clean as $row) {
        unlink($row);
      }
echo "﹒Logs cleared!\n";
    }
    if ($list=='') {
echo "﹒Choose what to clean!\n";
    }
  }

}