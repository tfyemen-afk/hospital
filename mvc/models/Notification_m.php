<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_m extends MY_Model
{
    protected $_table_name      = 'notification';
    protected $_primary_key     = 'notificationID';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = "notificationID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_notification($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_notification($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_notification($array)
    {
        $query = parent::get_single($array);
        return $query;
    }


    public function insert_notification($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_notification($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_notification($id)
    {
        parent::delete($id);
    }

    public function get_select_notification($select = 'patientID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_select_notification_with_limit($select = '*', $array = [], $limit = 5, $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('notificationID desc');
        if($limit > 0) {
            $this->db->limit($limit);
        }

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}