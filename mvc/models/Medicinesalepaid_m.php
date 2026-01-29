<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinesalepaid_m extends MY_Model
{
	protected $_table_name = 'medicinesalepaid';
	protected $_primary_key = 'medicinesalepaidID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "medicinesalepaidID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function get_medicinesalepaid($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_medicinesalepaid($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_medicinesalepaid($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_medicinesalepaid($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_medicinesalepaid($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_medicinesalepaid($id)
    {
		parent::delete($id);
	}

	public function get_medicinesalepaid_sum($clmn, $array)
    {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	public function get_select_medicinesalepaid($select = 'medicinesalepaidID, medicinesaleID, medicinesalepaidamount', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_medicinesalepaid_amount_order_by_date($array)
    {
        $this->db->select_sum('medicinesalepaidamount');
        $this->db->from('medicinesalepaid');
        $this->db->join('medicinesale', 'medicinesale.medicinesaleID=medicinesalepaid.medicinesaleID');
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('medicinesalepaiddate >=',$array['from_date']);
            $this->db->where('medicinesalepaiddate <=',$array['to_date']);
        }
        $this->db->where('medicinesale.medicinesalerefund', 0);
        return $this->db->get()->row();
    }

    public function get_order_by_medicinesalepaid_for_report($array, $single=TRUE)
    {
        $this->db->select('*');
        $this->db->from('medicinesalepaid');
        $this->db->join('medicinesale', 'medicinesale.medicinesaleID=medicinesalepaid.medicinesaleID');
        if($single) {
	        if(isset($array['from_date']) && isset($array['to_date'])) {
	            $this->db->where('medicinesalepaiddate >=',$array['from_date']);
	            $this->db->where('medicinesalepaiddate <=',$array['to_date']);
	        }
        } else {
        	$this->db->where('medicinesalepaid.medicinesalepaidyear', date('Y'));
        }
        $this->db->where('medicinesale.medicinesalerefund', 0);
        return $this->db->get()->result();
    }

    public function get_select_medicinesalepaid_with_medicinesale($select = '*', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('medicinesale', 'medicinesale.medicinesaleID = medicinesalepaid.medicinesaleID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by($this->_order_by);
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}