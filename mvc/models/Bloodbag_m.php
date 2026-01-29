<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bloodbag_m extends MY_Model
{
	protected $_table_name = 'bloodbag';
	protected $_primary_key = 'bloodbagID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bloodbagID asc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_bloodbag($select = 'bloodbagID, bagno', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_bloodbag($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}
	
	public function get_single_bloodbag($array)
    {
		$query = parent::get_single($array);
		return $query;
	}
	
	public function get_order_by_bloodbag($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}
	
	public function insert_bloodbag($array)
    {
		$error = parent::insert($array);
		return TRUE;
	}
	
	public function insert_batch_bloodbag($array)
    {
		return parent::insert_batch($array);
	}

	public function update_bloodbag($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_bloodbag($array, $column)
    {
        return parent::update_batch($array, $column);
    }

	public function delete_bloodbag($id)
    {
		parent::delete($id);
	}

	public function get_where_not_in_bloodbag($data, $array)
    {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where('bagno', $data);
		$this->db->where_not_in('bloodbagID', $array);
		return $this->db->get()->result();
	}
}