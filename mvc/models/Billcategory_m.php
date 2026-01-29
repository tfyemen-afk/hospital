<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billcategory_m extends MY_Model
{
	protected $_table_name      = 'billcategory';
	protected $_primary_key     = 'billcategoryID';
	protected $_primary_filter  = 'intval';
	protected $_order_by        = "billcategoryID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_billcategory($select = 'billcategoryID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_billcategory($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_billcategory($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_billcategory($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_billcategory($array)
    {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_billcategory($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_billcategory($id)
    {
		parent::delete($id);
	}
}