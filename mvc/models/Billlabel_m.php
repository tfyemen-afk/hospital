<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billlabel_m extends MY_Model {

	protected $_table_name = 'billlabel';
	protected $_primary_key = 'billlabelID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "billlabelID desc";

	function __construct() {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_billlabel($select = 'billlabelID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    function get_billlabel($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_billlabel($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_billlabel($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_billlabel($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_billlabel($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_billlabel($id){
		parent::delete($id);
	}
}