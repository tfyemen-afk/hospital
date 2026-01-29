<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bed_m extends MY_Model {

	protected $_table_name = 'bed';
	protected $_primary_key = 'bedID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bedID desc";

	function __construct() {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_bed($select = 'bedID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_bed($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_bed($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_bed($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_bed($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_bed($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_bed($id){
		parent::delete($id);
	}

	public function get_order_by_bed_for_report($array) {
	    $this->db->select('bed.*, patient.name as patientname');
	    $this->db->from($this->_table_name);
	    $this->db->join('patient', 'patient.patientID=bed.patientID', 'LEFT');
	    $this->db->where($array);
	    return $this->db->get()->result();
	}
}