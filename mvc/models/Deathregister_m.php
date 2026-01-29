<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deathregister_m extends MY_Model 
{
    protected $_table_name = 'deathregister';
    protected $_primary_key = 'deathregisterID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "deathregisterID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_select_deathregister($select = 'deathregisterID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_deathregister($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_deathregister($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_deathregister($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_deathregister($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_deathregister($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_deathregister($id)
    {
        parent::delete($id);
    }

    public function order($orderBy)
    {
        parent::order($orderBy);
    }
}