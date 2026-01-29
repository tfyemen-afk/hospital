<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blooddonor_m extends MY_Model
{
	protected $_table_name = 'blooddonor';
	protected $_primary_key = 'blooddonorID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "blooddonorID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_blooddonor($select = 'blooddonorID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_blooddonor($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_blooddonor($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_blooddonor($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_blooddonor($array)
    {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_blooddonor($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_blooddonor($id)
    {
		parent::delete($id);
	}

	public function get_order_by_blooddonor_for_report($array) {
		$this->db->select('*');
		$this->db->from('blooddonor');
		$this->db->join('bloodbag','bloodbag.donorID=blooddonor.blooddonorID');
		if(isset($array['bloodgroupID']) && (int)$array['bloodgroupID']) {
			$this->db->where('blooddonor.bloodgroupID',$array['bloodgroupID']);
		}
		if(isset($array['blooddonorID']) && (int)$array['blooddonorID']) {
			$this->db->where('blooddonor.blooddonorID',$array['blooddonorID']);
		}
		if(isset($array['patientID']) && (int)$array['patientID']) {
			$this->db->where('blooddonor.patientID',$array['patientID']);
		}
		if(isset($array['statusID']) && (int)$array['statusID']) {
			$this->db->where('bloodbag.status',$array['statusID']);
		}
		if(isset($array['bagno'])) {
			$this->db->where('bloodbag.bagno',$array['bagno']);
		}
		if(isset($array['from_date']) && isset($array['to_date'])) {
			$this->db->where('DATE(blooddonor.create_date) >=',$array['from_date']);
			$this->db->where('DATE(blooddonor.create_date) <=',$array['to_date']);
		}
		return $this->db->get()->result();
	}

}