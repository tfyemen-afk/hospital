<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense_m extends MY_Model
{
	protected $_table_name      = 'expense';
	protected $_primary_key     = 'expenseID';
	protected $_primary_filter  = 'intval';
	protected $_order_by        = "expenseID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function get_expense($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_select_expense($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_single_expense($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_expense($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_expense($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_expense($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_expense($id)
    {
		parent::delete($id);
	}

	public function get_expense_order_by_date($array)
    {
		$this->db->select_sum('amount');
		$this->db->from($this->_table_name);
		if(isset($array['from_date']) && isset($array['to_date'])) {
			$this->db->where('date >=',$array['from_date']);
			$this->db->where('date <=',$array['to_date']);
		}
		return $this->db->get()->row();
	}

	public function get_order_by_expense_for_report($array)
    {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		if(isset($array['from_date']) && isset($array['to_date'])) {
			$this->db->where('date >=',$array['from_date']);
			$this->db->where('date <=',$array['to_date']);
		}
		return $this->db->get()->result();
	}
}