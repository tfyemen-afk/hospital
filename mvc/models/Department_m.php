<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Department_m extends MY_Model
{
    protected $_table_name      = 'department';
    protected $_primary_key     = 'departmentID';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = "departmentID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_department($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_department($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_department($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_department($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_department($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_department($id)
    {
        parent::delete($id);
    }

    public function get_select_department_with_user($select = '*', $array = [], $limit = 5, $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.userID = department.create_userID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by($this->_order_by);
        if($limit > 0) {
            $this->db->limit($limit);
        }

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}