<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class birthregister_m extends MY_Model 
{
    protected $_table_name = 'birthregister';
    protected $_primary_key = 'birthregisterID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "birthregisterID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_select_birthregister($select = 'birthregisterID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_birthregister($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_birthregister($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_birthregister($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_birthregister($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_birthregister($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_birthregister($id)
    {
        parent::delete($id);
    }

    public function order($orderBy)
    {
        parent::order($orderBy);
    }
}