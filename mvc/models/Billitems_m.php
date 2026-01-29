<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billitems_m extends MY_Model
{
    protected $_table_name = 'billitems';
    protected $_primary_key = 'billitemsID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "billitemsID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_select_billitems($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_billitems($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_billitems($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_billitems($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_billitems($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_billitems($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_billitems($id)
    {
        parent::delete($id);
    }

    public function insert_batch_billitems($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_billitems($array, $column)
    {
        return parent::update_batch($array, $column);
    }

    public function delete_batch_billitems($array)
    {
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

    public function get_sum_billitems($clmn, $array = [])
    {
        return parent::get_sum($clmn, $array);
    }

    public function get_sum_with_discount_billitems($array = [])
    {
        $this->db->select('SUM(amount - ((amount/100) * discount)) AS billamount');
        $this->db->from($this->_table_name);
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $query = $this->db->get();
        $row = $query->row();
        if($row->billamount == null) {
            $object = (object) array('billamount' =>  0);
            return $object;
        } else {
            return $row;
        }
    }

    public function get_group_by_billitems($select = '*', $groupID, $array = [], $single = false)
    {
        return parent::get_group_by($select, $groupID, $array, $single);
    }
}