<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tpa_m extends MY_Model
{
	protected $_table_name          = 'tpa';
	protected $_primary_key         = 'tpaID';
	protected $_primary_filter      = 'intval';
	protected $_order_by            = "tpaID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_tpa($select = 'tpaID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_tpa($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_tpa($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_tpa($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_tpa($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_tpa($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_tpa($id)
    {
		parent::delete($id);
	}
}