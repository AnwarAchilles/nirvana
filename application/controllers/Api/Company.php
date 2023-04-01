<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends BaseApi
{

  # get latest data company
  public function latest_GET()
  {
    // setup query
    $QUERY = [
      'limit'=> '1',
      'order_by'=> [ 'id_company'=>'desc' ],
    ];
    // query data and set output
    $output = $this->models->get( $QUERY )->row_array();
    // return output
    $this->data = $output;
    $this->return(200);
  }

}