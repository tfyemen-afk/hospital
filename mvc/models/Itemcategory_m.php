<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcategory_m extends MY_Model {

	protected $_table_name = 'itemcategory';
	protected $_primary_key = 'itemcategoryID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemcategoryID desc";

	function __construct() {
		parent::__construct();
	}

	function get_itemcategory($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_itemcategory($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_itemcategory($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_itemcategory($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_itemcategory($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_itemcategory($id){
		parent::delete($id);
	}
}
