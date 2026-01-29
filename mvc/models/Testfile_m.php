<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testfile_m extends MY_Model
{
    protected $_table_name          = 'testfile';
    protected $_primary_key         = 'testfileID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "testfileID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_select_testfile($select = 'testfileID, name', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_testfile($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_testfile($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_testfile($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_testfile($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_testfile($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_testfile($id)
    {
        parent::delete($id);
    }

    public function get_select_testfile_with_test_bill($select = '*', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('test', 'test.testID = testfile.testID');
        $this->db->join('bill', 'bill.billID = test.billID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by($this->_order_by);
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

}