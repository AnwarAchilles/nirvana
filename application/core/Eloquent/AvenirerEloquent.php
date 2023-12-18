<?php

// Include the Avenirer_Model class
require_once PATH_APPLICATION.'/third_party/Avenirer_Model.php';

/**
 * Class CoreEloquent
 *
 * This class extends Avenirer_Model and provides additional functionality for working with Eloquent models.
 */
class CoreEloquent extends Avenirer_Model
{

  public $delete_cache_on_save = FALSE;

  public $cache_prefix = 'Avenirer';

  public $primary_key_order = 'ASC';

  /**
   * Initialize the builder with provided query conditions.
   *
   * @param array $Query An array of query conditions.
   * @return $this
   */
  public function builder( $Query='' ) {
    if (is_array($Query)) {
      foreach ($Query as $CIBuilder=>$SetQuery) {
        if (is_array($SetQuery)) {
          foreach ($SetQuery as $Key=>$Value) {
            if (method_exists($this->_database, $CIBuilder)) {
              $this->_database->$CIBuilder( $Key, $Value );
            }
          }
        }
      }
    }
    // $this->_database->like('name', 'Fast');
    return $this;
  }

  /**
   * Load related models specified in has_one, has_many, and has_many_pivot properties.
   *
   * @return $this
   */
  public function relation() {
    if (!empty($this->has_one)) {
      foreach ($this->has_one as $Name=>$SetHas) {
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
    $total = (array) $this->get_all();
    return count($total);
  }

  public function apiGetAll() {
    $this->db->order_by($this->primary_key, $this->primary_key_order);
    return $this->set_cache('list')->get_all();
  }

  public function apiGet( $id ) {
    return $this->set_cache('show_'.$id)->get( $id );
  }

  public function apiCreate( $data ) {
    $process = $this->insert( $data );
    $this->delete_cache('list');
    $this->cleanPaginate();
    return $process;
  }
  
  public function apiUpdate( $data, $id=null ) {
    if ($id==null) {
      $process = $this->update( $data );
    }else {
      $process = $this->update( $data, $id );
    }
    $this->delete_cache('show_'.$id);
    $this->delete_cache('list');
    $this->cleanPaginate();
    return $process;
  }
  
  public function apiDelete( $id ) {
    $process =  $this->delete( $id );
    $this->delete_cache('show_'.$id);
    $this->delete_cache('list');
    $this->cleanPaginate();
    return $process;
  }

  public function apiPaginate( $slice, $current, $total ) {
    $this->db->order_by($this->primary_key, $this->primary_key_order);
    return $this->set_cache('paginate_'.$total.'_'.$current)->paginate( $slice, $current );
  }
  public function cleanPaginate() {
    foreach ( glob(PATH_ARCHIVE.'/caches/*') as $row ) {
      if (str_contains($row, $this->cache_prefix.'_'.$this->table.'_paginate')) {
        $name = pathinfo($row, PATHINFO_FILENAME);
        $this->cache->file->delete($name);
      }
    }
  }

  public function apiEntries( $entry ) {
    $process = $this->insert($entry);
    $this->delete_cache('count');
    $this->delete_cache('list');
    $this->cleanPaginate();
    return $process;
  }

  public function apiRecache( $id ) {
    $this->delete_cache('show_'.$id);
    $this->delete_cache('list');
    $this->cleanPaginate();
    return true;
  }

  public function apiUncache() {
    $this->delete_cache('*');
    $this->cleanPaginate();
    return true;
  }
}
