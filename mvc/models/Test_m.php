<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_m extends MY_Model
{
	protected $_table_name      = 'test';
	protected $_primary_key     = 'testID';
	protected $_primary_filter  = 'intval';
	protected $_order_by        = "testID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_test($select = 'testID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_test($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_test($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_test($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_test($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_test($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_test($id)
    {
		parent::delete($id);
	}

	public function get_order_by_test_for_report($array)
    {
		$this->db->select('*');
        $this->db->from($this->_table_name);
        if(isset($array['testcategoryID']) && (int)$array['testcategoryID']) {
            $this->db->where('testcategoryID',$array['testcategoryID']);
        
            if(isset($array['testlabelID']) && (int)$array['testlabelID']) {
                $this->db->where('testlabelID',$array['testlabelID']);
            }
        }
        if(isset($array['billID']) && (int)$array['billID']) {
            $this->db->where('billID',$array['billID']);
        }
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('create_date >=',$array['from_date']);
            $this->db->where('create_date <=',$array['to_date']);
        } elseif(isset($array['from_date'])) {
            $this->db->where('create_date >=',$array['from_date']);
            $this->db->where('create_date <=',$array['to_date']);
        }
        return $this->db->get()->result();
	}

    public function get_select_test_with_bill($select = 'test.*, bill.*', $array = [], $single = FALSE)
    {
        $this->db->select($select);
        $this->db->from('test');
        $this->db->join('bill', 'bill.billID = test.billID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

	public function get_select_test_with_bill_patient($select = 'test.*, bill.*, patient.*', $array = [], $single = FALSE)
    {
        $this->db->select($select);
        $this->db->from('test');
        $this->db->join('bill', 'bill.billID = test.billID');
        $this->db->join('patient', 'patient.patientID = bill.patientID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        if($single) {
        	return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}