<?php defined('BASEPATH') or exit('No direct script access allowed');

use Virdiggg\SeederCi3\Seeder;

class Database extends CI_Controller
{
    public $seed;
    public function __construct()
    {
        parent::__construct();
        $this->seed = new Seeder();
    }

    public function migrate() {
			$this->load->library('migration');

			if (!$this->migration->latest()) {
				show_error($this->migration->error_string());
				return;
			}

			$res = $this->db->select('version')->from('migration')->get()->row();
			$msg = $this->seed->emoticon('MIGRATE NUMBER ' . $res->version . ' SUCCESS');

			print($msg);
			return;
    }

    public function rollback() {
			$this->load->library('migration');

			$resOld = $this->db->select('version')->from('migration')->get()->row();
			$version = $resOld->version;

			if (!$this->migration->version(0)) {
				show_error($this->migration->error_string());
				return;
			}

			$res = $this->db->select('version')->from('migration')->get()->row();
			$msg = $this->seed->emoticon('ROLLBACK MIGRATION TO NUMBER ' . $res->version . ' SUCCESS');

			print($msg);
			return;
    }

    public function seed() {
			// Get all arguments passed to this function
			$result = $this->seed->parseParam(func_get_args());
			$name = $result->name;
			// $args = $result->args; // Seeder doesn't have arguments.

			// You can set which database connection you want to use.
			// $this->seed->setConn('default2');
			$this->seed->setPath(PATH_ARCHIVE.'databases');
			$this->seed->seed($name);
		}

		public function migration() {
			// Get all arguments passed to this function
			$result = $this->seed->parseParam(func_get_args());
			$name = $result->name;
			$args = $result->args;

			// You can set which database connection you want to use.
			// $this->seed->setConn('default2');
			$this->seed->setPath(PATH_ARCHIVE.'databases');
			$this->seed->migration($name, $args);
    }

    // public function controller() {
		// 	// Get all arguments passed to this function

		// 	// Get all arguments passed to this function
		// 	$result = $this->seed->parseParam(func_get_args());
		// 	$name = $result->name;
		// 	$args = $result->args;

		// 	// $this->seed->setPath(APPPATH);
		// 	$this->seed->controller($name, $args);
		// 	return;
    // }

    // public function model() {
		// 	// Get all arguments passed to this function

		// 	// Get all arguments passed to this function
		// 	$result = $this->seed->parseParam(func_get_args());
		// 	$name = $result->name;
		// 	$args = $result->args;

		// 	// $this->seed->setPath(APPPATH);
		// 	$this->seed->model($name, $args);
		// 	return;
    // }
}