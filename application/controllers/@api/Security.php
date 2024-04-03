<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * Class Security
 *
 * This class represents a custom API controller that extends CoreApi.
 *
 * @class Security
 * @extends CoreApi
 */
class Security extends BaseApi
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
    'create_REST',
    'update_REST',
    'delete_REST',
  ];


  /**
   * Constructor for the Security class.
   * 
   * You can perform additional setup and logic here.
   */
  public function __construct() {
    parent::__construct();

    // Add your initialization code here
  }

  public function save_GET() {
    $result = [];
    $path = realpath(PATH_ROOT);
    if ($path !== false && $path !== '' && file_exists($path)) {
      foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
        if (
          (strpos($object->getPathname(), 'archive') === false) &&
          (strpos($object->getPathname(), 'node_modules') === false) &&
          (strpos($object->getPathname(), 'vendor') === false)
        ) {
          $catch = [];
          $catch['file'] = $object->getPathname();
          $catch['size'] = $object->getSize();
          $result[$object->getPathname()] = $catch;
        }
      }
    }
    if (file_exists(PATH_ROOT.'/security')) {
      unlink(PATH_ROOT.'/security');
    }

    file_put_contents(PATH_ROOT.'/security', serialize($result));
    $this->return(200, "Save Sucesss");
  }

  public function quarantine_POST() {
    $targetFrom = base64_decode($this->method('file'));
    $targetTo = PATH_ARCHIVE.'/security/'.uniqid().'_'.basename($targetFrom).'.quarantine';
    if (rename($targetFrom, $targetTo)) {
      $this->return(200, "Success Quarantine");
    }
  }

  public function delete_POST() {
    $targetFrom = base64_decode($this->method('file'));
    unlink($targetFrom);
    $this->return(200, "Success Delete");
  }

  public function load_GET() {
    $date = $this->method('date');
    $this->data = unserialize(file_get_contents(PATH_ROOT.'/security'));
    $this->return(200);
  }

  public function scan_GET() {
    $securityFile = unserialize(file_get_contents(PATH_ROOT.'/security'));
    
    $result = [];
    $path = realpath(PATH_ROOT);
    if ($path !== false && $path !== '' && file_exists($path)) {
      foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
        if (
          (strpos($object->getPathname(), 'archive') === false) &&
          (strpos($object->getPathname(), 'node_modules') === false) &&
          (strpos($object->getPathname(), 'vendor') === false)
        ) {
          if (!isset($securityFile[$object->getPathname()])) {
            $catch['state'] = 'NEW';
            $catch['file'] = $object->getPathname();
            $catch['size'] = $object->getSize();
            $result[] = $catch;
          }
          if (isset($securityFile[$object->getPathname()])) {
            if ((int) $object->getSize()!==(int)$securityFile[$object->getPathname()]['size']) {
              $catch['state'] = 'MODIFIED';
              $catch['file'] = $object->getPathname();
              $catch['size'] = $object->getSize();
              $result[] = $catch;
            }
          }
        }
      }
    }
    
    $this->data = $result;
    $this->return(200, "Scanning Success");
  }


}