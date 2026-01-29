<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bedtype_m extends MY_Model {

	protected $_table_name = 'bedtype';
	protected $_primary_key = 'bedtypeID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bedtypeID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_bedtype($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_bedtype($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_bedtype($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_bedtype($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_bedtype($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_bedtype($id){
		parent::delete($id);
	}
}