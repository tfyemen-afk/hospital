<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissionlog_m extends MY_Model 
{
    protected $_table_name = 'permissionlog';
    protected $_primary_key = 'permissionlogID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "permissionlogID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_permissionlog($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_permissionlog_with_feature($id = null) 
    {
        $query = "Select p1.permissionlogID,p1.name,p1.description, (case when p2.roleID = $id then 'yes' else 'no' end) as active From permissionlog p1 left join permissions p2 ON p1.permissionlogID = p2.permissionlogID and p2.roleID = $id order by p1.permissionlogID;";
        return $this->db->query($query)->result();
    }

    public function get_order_by_permissionlog($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_permissionlog($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_permissionlog($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_permissionlog($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_permissionlog($id)
    {
        parent::delete($id);
    }
}