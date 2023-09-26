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

  /**
   * CoreModel constructor.
   */
  public function __construct() {
    parent::__construct();

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
    return $this->db->get($this->table)->num_rows();
  }

  public function apiGetAll() {
    return $this->db->get($this->table)->result_array();
  }

  public function apiGet( $id ) {
    return $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
  }

  public function apiCreate( $data ) {
    return $this->db->insert( $this->table, $data );
  }

  public function apiUpdate( $data, $id ) {
    return $this->db->where($this->primary_key, $id)->update( $this->table, $data );
  }

  public function apiDelete( $id ) {
    return $this->db->where($this->primary_key, $id)->delete($this->table);
  }

  public function apiPaginate( $slice, $current ) {
    return $this->db->limit( $slice, $current )->get($this->table)->result_array();
  }

  public function apiEntries( $entry ) {
    return $this->db->insert_batch($this->table, $entry);
  }
}
