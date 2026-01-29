<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postcategories_m extends MY_Model {

	protected $_table_name = 'postcategories';
	protected $_primary_key = 'postcategoriesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "postcategoriesID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function get_postcategories($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_postcategories($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_postcategories($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_postcategories($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_postcategories($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_postcategories($id)
    {
		parent::delete($id);
	}
}