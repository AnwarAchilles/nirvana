<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include the CoreBase class
require_once PATH_APPLICATION."/core/CoreBase.php";

// Initialize the CoreBase instance
$BASE = new CoreBase();

// Load Eloquent ORM
$BASE->eloquent();

/**
 * Class CoreModel
 *
 * This class serves as the base model for your application.
 * It extends CI_Model and can include custom logic as needed.
 */
class CoreModel extends CI_Model
{

  public $cache_prefix = 'Core';

  /**
   * CoreModel constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->load->driver('cache');

    // Additional constructor logic can be added here if needed
  }



  /**
   * Builder method to construct queries.
   *
   * @param mixed $Query The query to build.
   * @return $this The current instance for method chaining.
   */
  public function builder($Query='') {
    if (is_array($Query)) {
      foreach ($Query as $CIBuilder=>$SetQuery) {
        if (is_array($SetQuery)) {
          foreach ($SetQuery as $Key=>$Value) {
            if (method_exists($this->model_db, $CIBuilder)) {
              $this->model_db->$CIBuilder($Key, $Value);
            }
          }
        }
      }
    }
    return $this;
  }

  /**
   * Handle relationships by eager loading.
   *
   * @return $this The current instance for method chaining.
   */
  public function relation() {
    if (!empty($this->belongs_to)) {
      foreach ($this->belongs_to as $Name=>$SetHas) {
        $this->with($Name);
      }
    }
    if (!empty($this->has_many)) {
      foreach ($this->has_many as $Name=>$SetHas) {
        $this->with($Name);
      }
    }
    if (!empty($this->has_many_pivot)) {
      foreach ($this->has_many_pivot as $Name=>$SetHas) {
        $this->with($Name);
      }
    }
    return $this;
  }

  /**
   * API methods for common operations on the model.
   */
  public function apiCountRows() {
    $cached_data = $this->cache->file->get($this->cachePrefix('_count'));
    if (!$cached_data) {
      $cached_data = $this->db->get($this->table)->num_rows();
      $this->cache->file->save($this->cachePrefix('_count'), $cached_data, 86400);
    }
    return $cached_data;
  }

  public function apiGetAll() {
    $cached_data = $this->cache->file->get($this->cachePrefix('_list'));
    if (!$cached_data) {
      $cached_data = $this->db->get($this->table)->result_array();
      $this->cache->file->save($this->cachePrefix('_list'), $cached_data, 86400);
    }
    return $cached_data;
  }

  public function apiGet( $id ) {
    $cached_data = $this->cache->file->get($this->cachePrefix('_list'));
    if (!$cached_data) {
      $cached_data = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
      $this->cache->file->save($this->cachePrefix('_show_'.$id), $cached_data, 86400);
    }
    return $cached_data;
  }

  public function apiCreate( $data ) {
    $process = $this->db->insert( $this->table, $data );
    $this->cache->file->delete($this->cachePrefix('_count'));
    $this->cache->file->delete($this->cachePrefix('_list'));
    $this->cleanPaginate();
    return $process;
  }

  public function apiUpdate( $data, $id ) {
    $process = $this->db->where($this->primary_key, $id)->update( $this->table, $data );
    $this->cache->file->delete($this->cachePrefix('_count'));
    $this->cache->file->delete($this->cachePrefix('_list'));
    $this->cache->file->delete($this->cachePrefix('_show_'.$id));
    $this->cleanPaginate();
    return $process;
  }

  public function apiDelete( $id ) {
    $process = $this->db->where($this->primary_key, $id)->delete($this->table);
    $this->cache->file->delete($this->cachePrefix('_count'));
    $this->cache->file->delete($this->cachePrefix('_list'));
    $this->cache->file->delete($this->cachePrefix('_show_'.$id));
    $this->cleanPaginate();
    return $process;
  }

  public function apiPaginate( $slice, $current, $total ) {
    $cached_data = $this->cache->file->get($this->cachePrefix('_paginate_'.$total.'_'.$current));
    if (!$cached_data) {
      $cached_data = $this->db->limit( $slice, $current )->get($this->table)->result_array();
      $this->cache->file->save($this->cachePrefix('_paginate_'.$total.'_'.$current), $cached_data, 86400);
    }
    return $cached_data;
  }

  public function cleanPaginate() {
    foreach ( glob(PATH_ARCHIVE.'/caches/*') as $row ) {
      if (str_contains($row, $this->cache_prefix.'_'.$this->table.'_paginate_')) {
        $name = pathinfo($row, PATHINFO_FILENAME);
        $name = str_replace($this->cache_prefix.'_', '', $name);
        $name = str_replace($this->table.'_', '_', $name);
        $this->cache->file->delete($this->cachePrefix($name));
      }
    }
  }

  public function apiEntries( $entry ) {
    $process = $this->db->insert_batch($this->table, $entry);
    $this->cache->file->delete($this->cachePrefix('_count'));
    $this->cache->file->delete($this->cachePrefix('_list'));
    $this->cleanPaginate();
    return $process;
  }

  private function cachePrefix( $name ) {
    return $this->cache_prefix.'_'.$this->table.$name;
  }

}
