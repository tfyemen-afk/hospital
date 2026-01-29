<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salaryoption_m extends MY_Model
{
	protected $_table_name = 'salaryoption';
	protected $_primary_key = 'salaryoptionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "salaryoptionID desc";

	public function __construct()
    {
		parent::__construct();
	}

    public function order($order)
    {
        parent::order($order);
    }

	public function get_salaryoption($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_salaryoption($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_salaryoption($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_salaryoption($array)
    {
		$error = parent::insert($array);
		return true;
	}

	public function update_salaryoption($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_salaryoption($id)
    {
		parent::delete($id);
		return true;
	}

	public function delete_salaryoption_by_salarytemplateID($id)
    {
		$this->db->delete($this->_table_name, array('salarytemplateID' => $id)); 
	}
}