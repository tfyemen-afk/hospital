<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prescriptionitem_m extends MY_Model
{
    protected $_table_name = 'prescriptionitem';
    protected $_primary_key = 'prescriptionitemID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "prescriptionitemID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_prescriptionitem($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_prescriptionitem($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_prescriptionitem($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_prescriptionitem($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_prescriptionitem($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_prescriptionitem($id)
    {
        parent::delete($id);
    }

    public function insert_batch_prescriptionitem($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_prescriptionitem($array, $column)
    {
        return parent::update_batch($array, $column);
    }

    public function delete_prescriptionitem_by_prescriptionID($id)
    {
        $this->db->where('prescriptionID', $id);
        $this->db->delete($this->_table_name);
        return true;
    }
}