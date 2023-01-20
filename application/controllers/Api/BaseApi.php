<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class BaseApi extends CoreApi
{
  
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
  public function __construct() {
    parent::__construct();

    // do something here
  }

  /* ---- ---- ---- ----
   * REST LIST
   * ---- ---- ---- ---- */
  public function list_REST()
  {
    $QUERY = $this->query;

    $this->data = $this->models->get( $QUERY )->result_array();
    
    $this->return(200);
  }

  /* ---- ---- ---- ----
   * REST SHOW
   * ---- ---- ---- ---- */
  public function show_REST()
  {
    $QUERY = $this->query;
    if ($this->config->api['prefix_id'] == TRUE) {
      $QUERY['where']['id_'.$this->models->table] = $this->id;
    }else {
      $QUERY['where'] = ['id'=>$this->id];
    }

    $this->data = $this->models->get( $QUERY )->row_array();
    
    $this->return(200);
  }

  /* ---- ---- ---- ----
   * REST CREATE
   * ---- ---- ---- ---- */
  public function create_REST()
  {
    $QUERY = $this->query;
    $QUERY['data'] = $this->method;

    $this->data = $this->models->set( $QUERY );

    $this->return(200);
  }
  
  /* ---- ---- ---- ----
   * REST UPDATE
   * ---- ---- ---- ---- */
  public function update_REST()
  {
    $QUERY = $this->query;
    if ($this->config->api['prefix_id'] == TRUE) {
      $QUERY['where']['id_'.$this->models->table] = $this->id;
    }else {
      $QUERY['where'] = ['id'=>$this->id];
    }
    $QUERY['data'] = $this->method;

    $this->data = $this->models->put( $QUERY );

    $this->return(200);
  }

  /* ---- ---- ---- ----
   * REST DELETE
   * ---- ---- ---- ---- */
  public function delete_REST()
  {
    $QUERY = $this->query;
    if ($this->config->api['prefix_id'] == TRUE) {
      $QUERY['where']['id_'.$this->models->table] = $this->id;
    }else {
      $QUERY['where'] = ['id'=>$this->id];
    }

    $this->data = $this->models->del( $QUERY );

    $this->return(200);
  }

  /* ---- ---- ---- ----
   * FIELD LIST
   * ---- ---- ---- ---- */
  public function fields_GET() {
    $this->data = $this->models->fields();
    $this->return(200);
  }

  /* ---- ---- ---- ----
   * REST DATATABLE
   * ---- ---- ---- ---- */
  public function datatable_REST()
  {
    if (!isset($this->method['draw'])) {
      $this->out['message'] = 'access only allowed with datatable plugin';
      $this->return( 406 );
    }

    $draw   = $this->method['draw'];
		$length = $this->method['length'];
		$start  = $this->method['start'];
    $columns = $this->method['columns'];
		$order_column  = $this->method['order'][0]['column'];
		$order_dir  = $this->method['order'][0]['dir'];
		$search = $this->method['search']["value"];
    $total  = $this->db->count_all_results($this->models->table);
    $output = array();

    $order_column = $columns[$order_column]['data'];
		
    $output['draw']=$draw;

    $output['order'][] = ['columns'=>$order_column, 'dir'=>$order_dir];

    $output['recordsTotal']=$output['recordsFiltered']=$total;

		$output['data']=array();

    $fields = $this->db->list_fields($this->models->table);

		if($search!=""){
      foreach ($fields as $key=>$column) {
        $this->db->or_like($column, $search);
      }
		}

		$this->db->limit($length, $start);
		
    if (($order_column!=='no') AND ($order_column!=='button')) {
      $this->db->order_by($order_column, $order_dir);
    }
		
    $query=$this->db->get($this->models->table);

		if($search!=""){
      foreach ($fields as $key=>$column) {
        $this->db->or_like($column, $search);
      }
      $jum = $this->db->get($this->models->table);
      $output['recordsTotal'] = $output['recordsFiltered'] = $jum->num_rows();
		}

		$nomor_urut = $start+1;
		foreach ($query->result_array() as $row) {
      $row['no'] = $nomor_urut;
      $row['button'] = '';
      $output['data'][] = $row;
		  $nomor_urut++;
		}

    $this->return = $output;
    $this->return( 200 );
  }





  /* ---- ---- ---- ----
   * METHOD DATATABLE
   * ---- ---- ---- ---- */
  public function datatable_GET() { $this->datatable_REST(); }
  public function datatable_POST() { $this->datatable_REST(); }


  public function modal_POST() {
    $this->data = $this->load->view( $this->method['modal'].'.html', null, true);
    $this->return(200);
  }

  public function count_GET() {
    $this->data = $this->models->get( $this->query )->num_rows();
    $this->return(200);
  }


  // add extra interactions to dbms here

}