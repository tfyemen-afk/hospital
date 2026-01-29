<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table_name = 'item';
	protected $_primary_key = 'itemID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "itemID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_item($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_item($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_item($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_item($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_item($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_item($id){
		parent::delete($id);
	}

	public function get_select_item($select = 'itemID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

}