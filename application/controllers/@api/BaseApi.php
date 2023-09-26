<?php


/**
 * Class BaseApi
 *
 * This class provides a base structure for handling RESTful API operations.
 */
class BaseApi extends CoreApi
{
  /**
   * List resources.
   *
   * @request: GET
   * @endpoint: /api/table
   */
  public function list_REST()
  {
    // Model Eloquent 1
    $this->models->builder($this->query);
    $this->models->relation();
    $this->return['total'] = $this->models->apiCountRows();
    $this->data = $this->models->apiGetAll();

    if ($this->data) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Show a single resource.
   *
   * @request: GET
   * @endpoint: /api/table/{id}
   */
  public function show_REST()
  {
    // Model Eloquent 1
    $this->models->builder($this->query);
    $this->models->relation();
    $this->data = $this->models->apiGet($this->id);

    if ($this->data) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Create a new resource.
   *
   * @request: GET, POST
   * @endpoint: /api/table
   */
  public function create_REST()
  {
    // Model Eloquent 1
    $this->models->builder($this->query);
    $isCreate = $this->models->apiCreate($this->serialNumber($this->method));
    $this->data[$this->models->primary_key] = $isCreate;

    if ($isCreate) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Update an existing resource.
   *
   * @request: GET, POST, PUT
   * @endpoint: /api/table/{id}
   */
  public function update_REST()
  {
    if ($this->id) {
      // Model Eloquent 1
      $this->models->builder($this->query);
      $isUpdate = $this->models->apiUpdate($this->method, $this->id);

      if ($isUpdate) {
        $this->models->builder($this->query);
        $this->models->relation();
        $this->data = $this->models->apiGet($this->id);

        $this->return(200, "Success");
      } else {
        $this->return(400, "Failed");
      }
    } else {
      $this->return(400, "ID required");
    }
  }

  /**
   * Delete a resource.
   *
   * @request: GET, POST, DELETE
   * @endpoint: /api/table/{id}
   */
  public function delete_REST()
  {
    if ($this->id) {
      // Model Eloquent 1
      $this->data = $this->models
        ->builder($this->query)
        ->relation()
        ->apiGet($this->id);
      $isDelete = $this->models
        ->builder($this->query)
        ->apiDelete($this->id);
      if ($isDelete) {
        $this->return(200, "Success");
      } else {
        $this->return(400, "Failed");
      }
    } else {
      $this->return(400, "ID required");
    }
  }

  /**
   * Paginate resources.
   *
   * @request: GET
   * @endpoint: /api/table/paginate
   */
  public function paginate_GET()
  {
    // Model Eloquent 1
    $this->models->builder($this->query);
    $this->models->relation();
    $current = (empty($this->id)) ? 1 : $this->id;
    $total = $this->models->apiCountRows();

    $slice = $this->models->paginate;
    $this->return['total'] = $total;
    $this->return['paginate']['current'] = $current;
    $this->return['paginate']['total'] = ceil($total / $slice);
    $this->return['paginate']['slice'] = $slice;
    $this->data = $this->models->apiPaginate($slice, $current);

    $this->return(200);
  }

  /**
   * Handle resource entries.
   *
   * @request: POST
   * @endpoint: /api/table/entries
   */
  public function entries_POST()
  {
    // Model Eloquent 1
    $this->models->builder($this->query);
    $isCreate = $this->models->apiEntries($this->serialNumber($this->method));
    $this->data[$this->models->primary_key] = $isCreate;

    if ($isCreate) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }
}
