<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_m extends MY_Model
{
    protected $_table_name          = 'attendance';
    protected $_primary_key         = 'attendanceID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "attendanceID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_attendance($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_attendance($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_attendance($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_attendance($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_attendance($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_attendance($id)
    {
        parent::delete($id);
    }

    public function insert_batch_attendance($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_attendance($array, $column)
    {
        return parent::update_batch($array, $column);
    }
}