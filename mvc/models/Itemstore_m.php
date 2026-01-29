<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemstore_m extends MY_Model {

	protected $_table_name = 'itemstore';
	protected $_primary_key = 'itemstoreID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemstoreID desc";

	function __construct() {
		parent::__construct();
	}

	function get_itemstore($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_itemstore($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_itemstore($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_itemstore($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_itemstore($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_itemstore($id){
		parent::delete($id);
	}

	public function get_select_itemstore($select = 'itemstoreID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

}

