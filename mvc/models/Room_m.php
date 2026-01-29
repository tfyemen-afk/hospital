<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_m extends MY_Model
{
    protected $_table_name = 'room';
    protected $_primary_key = 'roomID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "roomID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_select_room($select = 'roomID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_room($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_room($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_room($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_room($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_room($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_room($id)
    {
        parent::delete($id);
    }

    public function insert_batch_room($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_room($array, $column)
    {
        return parent::update_batch($array, $column);
    }
}