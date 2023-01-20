<?php
defined('BASEPATH') OR exit('No direct script access allowed');



# SET CORE MODEL
class CoreModel extends CI_Model
{
	

	/* ---- ---- ---- ----
   * MAIN VARIABLE
   * ---- ---- ---- ---- */
	public $table;


	/* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
	public function __construct()
	{
		parent::__construct();
		// setup here
	}


	/* ---- ---- ---- ----
   * RESULT or ROWS DATA
	 * method: get, view
   * ---- ---- ---- ---- */
	public function get( $options=[] ){
		$this->db->from($this->table);
		if (empty($options)) {
			$return = $this->db->get();
		}else {
			foreach ($options as $key=>$val) {
				if (is_array($val)) {
					foreach ($val as $key2=>$val2) {
						if ( ($key=='join') ) {
							$this->db->$key($key2, $val2, 'left');
						}else {
							$this->db->$key($key2, $val2);
						}
					}
				}else {
					$this->db->$key($val);
				}
			}
			$return = $this->db->get();
		}
		return $return;
	}
	public function view( $options=[] ){
		return $this->get( $options );
	}


	/* ---- ---- ---- ----
   * INSERT DATA
	 * method: set, insert, create
   * ---- ---- ---- ---- */
	public function set( $options=[] ){
		$this->db->insert($this->table, $options['data']);
		return $this->db->insert_id();
	}
	public function insert( $options=[] ){
		return $this->set( $options );
	}
	public function create( $options=[] ){
		return $this->set( $options );
	}
	

	/* ---- ---- ---- ----
   * UPDATE DATA
	 * method: put, update
   * ---- ---- ---- ---- */
	public function put( $options=[] ){
		$this->db->update($this->table, $options['data'], $options['where']);
	}
	public function update( $options=[] ){
		return $this->put( $options );
	}

	
	/* ---- ---- ---- ----
   * DELETE DATA
	 * method: del, delete
   * ---- ---- ---- ---- */
	public function del( $options=[] ){
		$this->db->delete($this->table, $options['where']);
	}
	public function delete( $options=[] ){
		return $this->del( $options );
	}


	/* ---- ---- ---- ----
   * FIELD DATA
   * ---- ---- ---- ---- */
	public function fields() {
		return $this->db->list_fields( $this->table );
	}
	
}




# load base
require_once APPPATH."/core/CoreBase.php";

# set base model
$BASE = new CoreBase();
$BASE->model();