<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword_m extends MY_Model {

	protected $_table_name = 'resetpassword';
	protected $_primary_key = 'resetpasswordID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "resetpasswordID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_resetpassword($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_resetpassword($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_resetpassword($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_resetpassword($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_resetpassword($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_resetpassword($id){
		parent::delete($id);
	}
}