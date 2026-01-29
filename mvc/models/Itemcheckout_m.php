<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcheckout_m extends MY_Model {

	protected $_table_name = 'itemcheckout';
	protected $_primary_key = 'itemcheckoutID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemcheckoutID desc";

	function __construct() {
		parent::__construct();
	}

	function get_itemcheckout($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_sum_itemcheckout($clmn, $array = []) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	function get_single_itemcheckout($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_itemcheckout($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_itemcheckout($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_itemcheckout($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_itemcheckout($id){
		parent::delete($id);
	}
}

