<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postcategory_m extends MY_Model
{
    protected $_table_name = 'postcategory';
    protected $_primary_key = 'postcategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "postcategoryID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_postcategory($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_postcategory($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_postcategory($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_postcategory($array)
    {
        $id = parent::insert($array);
        return $id;
    }

    public function update_postcategory($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_postcategory($id)
    {
        parent::delete($id);
    }

    public function delete_postcategory_by_array($array)
    {
        $this->db->delete($this->_table_name, $array);
        return TRUE;
    }
}