<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Floor_m extends MY_Model {

	protected $_table_name = 'floor';
	protected $_primary_key = 'floorID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "floorID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_floor($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_floor($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_floor($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_floor($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_floor($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_floor($id){
		parent::delete($id);
	}
}