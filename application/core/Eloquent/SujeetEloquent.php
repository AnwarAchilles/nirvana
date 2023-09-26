<?php
require_once PATH_APPLICATION.'/third_party/Sujeet_Model.php';

/**
 * Class CoreEloquent
 *
 * Custom Eloquent Model class extending Sujeet_Model.
 * It provides additional functionality for building queries and handling relationships.
 */
class CoreEloquent extends Sujeet_Model {

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
   * Get the count of all records.
   *
   * @return int The count of records.
   */
  public function apiCountRows() {
    return $this->countAll();
  }

  /**
   * Get all records.
   *
   * @return array All records.
   */
  public function apiGetAll() {
    return $this->findAll();
  }

  /**
   * Get a single record by ID.
   *
   * @return mixed The record.
   */
  public function apiGet() {
    return $this->find($this->id);
  }

  /**
   * Create a new record.
   *
   * @param array $data The data to insert.
   * @return mixed The inserted record.
   */
  public function apiCreate($data) {
    return $this->create($data);
  }

  /**
   * Update a record by ID.
   *
   * @param int $id The ID of the record to update.
   * @param array $data The data to update.
   * @return bool True if the update was successful, otherwise false.
   */
  public function apiUpdate($id, $data) {
    return $this->updateById($id, $data);
  }

  /**
   * Delete a record by ID.
   *
   * @param int $id The ID of the record to delete.
   * @return bool True if the deletion was successful, otherwise false.
   */
  public function apiDelete($id) {
    return $this->deleteById($id);
  }

  /**
   * Paginate records.
   *
   * @param int $slice The number of records per page.
   * @param int $current The current page number.
   * @return array Paginated records.
   */
  public function apiPaginate($slice, $current) {
    $current = ($current - 1) * $slice;
    return $this->limit($slice, $current)->findAll();
  }

  /**
   * Insert multiple entries.
   *
   * @param array $entry The entries to insert.
   * @return array IDs of the inserted entries.
   */
  public function apiEntries($entry) {
    $ids = [];
    foreach ($entry as $data) {
      $ids[] = $this->save($data);
    }
    return $ids;
  }
}
