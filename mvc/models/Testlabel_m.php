<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testlabel_m extends MY_Model {

	protected $_table_name          = 'testlabel';
	protected $_primary_key         = 'testlabelID';
	protected $_primary_filter      = 'intval';
	protected $_order_by            = "testlabelID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_testlabel($select = 'testlabelID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_testlabel($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_testlabel($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_testlabel($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_testlabel($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_testlabel($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_testlabel($id)
    {
		parent::delete($id);
	}
}