<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damageandexpire_m extends MY_Model {

	protected $_table_name = 'damageandexpire';
	protected $_primary_key = 'damageandexpireID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "damageandexpireID desc";

	function __construct() {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_damageandexpire($select = 'damageandexpireID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_damageandexpire($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_damageandexpire($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_damageandexpire($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_damageandexpire($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_damageandexpire($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_damageandexpire($id){
		parent::delete($id);
	}
}