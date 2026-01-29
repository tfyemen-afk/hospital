<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menulog_m extends MY_Model 
{
    protected $_table_name = 'menulog';
    protected $_primary_key = 'menulogID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "menulogID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_menulog($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_menulog($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_menulog($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_menulog($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_menulog($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_menulog($id)
    {
        parent::delete($id);
    }

    public function get_select_menulog($select = 'menulogID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

}