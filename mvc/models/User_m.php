<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_m extends MY_Model
{
    protected $_table_name     = 'user';
    protected $_primary_key    = 'userID';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "userID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_user($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_user($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_user($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_user($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_user($id)
    {
        parent::delete($id);
    }

    public function hash($string)
    {
        return parent::hash($string);
    }

    public function get_select_user($select = 'userID, name', $array = [], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_select_doctor_with_doctorinfo($select = 'userID, name', $array = [])
    {
        $this->db->select('user.userID, user.name');
        $this->db->from($this->_table_name);
        $this->db->join('doctorinfo', 'user.userID=doctorinfo.userID', 'LEFT');
        if(isset($array['departmentID'])) {
            $this->db->where('doctorinfo.departmentID', $array['departmentID']);
        }
        $this->db->where('user.designationID', $array['designationID']);
        $this->db->where(['user.status' => 1, 'user.delete_at' => 0]);
        return $this->db->get()->result();
    }

    public function get_user_with_user_email($username, $password, $single = false)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where(['password' => $password]);
        $this->db->where(['delete_at' => 0]);
        $this->db->where('(username = "' . $username . '" OR email = "' . $username . '")');
        if ($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}
