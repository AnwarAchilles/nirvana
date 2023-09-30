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
    return $this->count_rows();
  }

  public function apiGetAll() {
    return $this->get_all();
  }

  public function apiGet( $id ) {
    return $this->get( $id );
  }

  public function apiCreate( $data ) {
    return $this->insert( $data );
  }

  public function apiUpdate( $data, $id ) {
    return $this->update( $data, $id );
  }

  public function apiDelete( $id ) {
    return $this->delete( $id );
  }

  public function apiPaginate( $slice, $current ) {
    return $this->paginate( $slice, $current );
  }

  public function apiEntries( $entry ) {
    return $this->insert($entry);
  }
}
