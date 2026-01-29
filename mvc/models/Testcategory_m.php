<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testcategory_m extends MY_Model
{
	protected $_table_name      = 'testcategory';
	protected $_primary_key     = 'testcategoryID';
	protected $_primary_filter  = 'intval';
	protected $_order_by        = "testcategoryID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_testcategory($select = 'testcategoryID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_testcategory($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_testcategory($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_testcategory($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_testcategory($array)
    {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_testcategory($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_testcategory($id)
    {
		parent::delete($id);
	}
}