<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemsupplier_m extends MY_Model {

	protected $_table_name = 'itemsupplier';
	protected $_primary_key = 'itemsupplierID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemsupplierID desc";

	function __construct() {
		parent::__construct();
	}

	function get_itemsupplier($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_itemsupplier($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_itemsupplier($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_itemsupplier($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_itemsupplier($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_itemsupplier($id){
		parent::delete($id);
	}

	public function get_select_itemsupplier($select = 'itemsupplierID, companyname', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

}
