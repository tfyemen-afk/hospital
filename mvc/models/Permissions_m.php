<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions_m extends MY_Model 
{
    protected $_table_name = 'permissions';

    public function __construct() 
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_permissions($array = NULL, $signal = FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_permissions($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_permissions($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_permissions($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_permissions($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_permissions($id)
    {
        parent::delete($id);
    }

    public function insert_batch_permissions($array) 
    {
        return parent::insert_batch($array);
    }

    public function delete_permissions_by_role($roleID) 
    {
        $this->db->where('roleID', $roleID);
        $this->db->delete($this->_table_name);
    }
}