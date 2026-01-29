<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_m extends MY_Model 
{

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_order_by_search_for_user_patient($search) 
	{
	    $this->db->select('user.userID, user.name, user.email, user.phone, user.designationID, user.roleID, user.patientID');
	    $this->db->from('user');
	    $this->db->join('patient', 'patient.patientID=user.patientID','LEFT');
	    $this->db->group_start();
		    $this->db->like('user.name', $search, 'both');
		    $this->db->or_like('user.email', $search, 'both');
		    $this->db->or_like('user.phone', $search, 'both');
		    $this->db->or_like('user.address', $search, 'both');
		    $this->db->or_like('user.username', $search, 'both');
		    $this->db->or_like('user.religion', $search, 'both');
		    $this->db->or_like('user.description', $search, 'both');
		    $this->db->or_like('patient.guardianname', $search, 'both');
		    $this->db->or_like('patient.patientID', $search, 'both');
	    $this->db->group_end();
	    $this->db->where('user.delete_at', 0);
	    $this->db->where('user.status', 1);
	    return $this->db->get()->result();
	}

	public function get_order_by_search_for_user($search) 
	{
	    $this->db->select('userID, name, email, phone, designationID, roleID, patientID');
	    $this->db->from('user');
	    $this->db->group_start();
		    $this->db->like('name', $search, 'both');
		    $this->db->or_like('email', $search, 'both');
		    $this->db->or_like('phone', $search, 'both');
		    $this->db->or_like('address', $search, 'both');
		    $this->db->or_like('username', $search, 'both');
		    $this->db->or_like('religion', $search, 'both');
		    $this->db->or_like('description', $search, 'both');
		$this->db->group_end();
	    $this->db->where('roleID !=', 3);
	    $this->db->where('delete_at', 0);
	    $this->db->where('status', 1);
	    return $this->db->get()->result();
	}

	public function get_order_by_search_for_patient($search) 
	{
	    $this->db->select('user.userID, user.name, user.email, user.phone, user.designationID, user.roleID, user.patientID');
	    $this->db->from('user');
	    $this->db->join('patient', 'patient.patientID=user.patientID','LEFT');
	    $this->db->group_start();
		    $this->db->like('user.name', $search, 'both');
		    $this->db->or_like('user.email', $search, 'both');
		    $this->db->or_like('user.phone', $search, 'both');
		    $this->db->or_like('user.address', $search, 'both');
		    $this->db->or_like('user.username', $search, 'both');
		    $this->db->or_like('user.religion', $search, 'both');
		    $this->db->or_like('user.description', $search, 'both');
		    $this->db->or_like('patient.guardianname', $search, 'both');
		    $this->db->or_like('patient.patientID', $search, 'both');
		$this->db->group_end();
	    $this->db->where('roleID', 3);
	    $this->db->where('user.delete_at', 0);
	    $this->db->where('user.status', 1);
	    return $this->db->get()->result();
	}

}