<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bloodgroup_m extends MY_Model 
{
	protected $_table_name = 'bloodgroup';
	protected $_primary_key = 'bloodgroupID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bloodgroupID asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_bloodgroup($select ='bloodgroupID, bloodgroup', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_bloodgroup($array=NULL, $signal=FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_bloodgroup($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_bloodgroup($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}
	
	public function insert_bloodgroup($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}
	
	public function update_bloodgroup($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}
	
	public function delete_bloodgroup($id)
	{
		parent::delete($id);
	}
}