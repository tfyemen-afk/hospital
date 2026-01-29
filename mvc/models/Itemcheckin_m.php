<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcheckin_m extends MY_Model {

	protected $_table_name = 'itemcheckin';
	protected $_primary_key = 'itemcheckinID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemcheckinID desc";

	function __construct() {
		parent::__construct();
	}

	function get_itemcheckin($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_sum_itemcheckin($clmn, $array = []) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	function get_single_itemcheckin($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_itemcheckin($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_itemcheckin($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_itemcheckin($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_itemcheckin($id){
		parent::delete($id);
	}

	function update_itemcheckin_itemID($array, $id = NULL) {
		$this->db->set($array);
		$this->db->where('itemID', $id);
		return $this->db->update($this->_table_name);
	}

}