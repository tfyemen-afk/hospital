<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinepurchasepaid_m extends MY_Model
{
	protected $_table_name = 'medicinepurchasepaid';
	protected $_primary_key = 'medicinepurchasepaidID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "medicinepurchasepaidID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function get_medicinepurchasepaid($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_medicinepurchasepaid($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_medicinepurchasepaid($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_medicinepurchasepaid($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_medicinepurchasepaid($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_medicinepurchasepaid($id)
    {
		parent::delete($id);
	}

	public function get_medicinepurchasepaid_sum($clmn, $array)
    {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	public function get_select_medicinepurchasepaid($select = 'medicinepurchasepaidID, medicinepurchaseID, medicinepurchasepaidamount', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_medicinepurchasepaid_amount_order_by_date($array)
    {
        $this->db->select_sum('medicinepurchasepaidamount');
        $this->db->from('medicinepurchasepaid');
        $this->db->join('medicinepurchase', 'medicinepurchase.medicinepurchaseID=medicinepurchasepaid.medicinepurchaseID');
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('medicinepurchasepaiddate >=',$array['from_date']);
            $this->db->where('medicinepurchasepaiddate <=',$array['to_date']);
        }
        $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
        return $this->db->get()->row();
    }

    public function get_order_by_medicinepurchasepaid_for_report($array, $single=TRUE)
    {
        $this->db->select('*');
        $this->db->from('medicinepurchasepaid');
        $this->db->join('medicinepurchase', 'medicinepurchase.medicinepurchaseID=medicinepurchasepaid.medicinepurchaseID');
        if($single) {
	        if(isset($array['from_date']) && isset($array['to_date'])) {
	            $this->db->where('medicinepurchasepaiddate >=',$array['from_date']);
	            $this->db->where('medicinepurchasepaiddate <=',$array['to_date']);
	        }
        } else {
        	$this->db->where('medicinepurchasepaid.medicinepurchaseyear', date('Y'));
        }
        $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
        return $this->db->get()->result();
    }
}