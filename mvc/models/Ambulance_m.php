<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ambulance_m extends MY_Model 
{
    protected $_table_name          = 'ambulance';
    protected $_primary_key         = 'ambulanceID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "ambulanceID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_ambulance($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_ambulance($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_ambulance($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_ambulance($array) 
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_ambulance($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_ambulance($id)
    {
        parent::delete($id);
    }

    public function order($orderBy)
    {
        parent::order($orderBy);
    }

    public function get_select_ambulance($select = 'ambulanceID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }
}