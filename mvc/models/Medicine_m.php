<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicine_m extends MY_Model {

	protected $_table_name = 'medicine';
	protected $_primary_key = 'medicineID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "medicineID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_select_medicine($select = 'medicineID, name, medicinecategoryID', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

	public function get_medicine($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_medicine($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_medicine($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_medicine($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_medicine($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_medicine($id){
		parent::delete($id);
	}
}