<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instruction_m extends MY_Model
{
    protected $_table_name = 'instruction';
    protected $_primary_key = 'instructionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "instructionID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_instruction($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_instruction($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_instruction($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_instruction($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_instruction($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_instruction($id)
    {
        parent::delete($id);
    }

    public function get_select_instruction($select = 'instruction', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_instruction_with_user($array=[])
    {
        $this->db->select('instruction.*, user.name, user.photo');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.userID = instruction.create_userID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('instruction.create_date', 'desc');
        return $this->db->get()->result();
    }

    public function get_instruction_with_admission($array=[], $single = false)
    {
        $this->db->select('instruction.*, admission.doctorID');
        $this->db->from($this->_table_name);
        $this->db->join('admission', 'admission.admissionID = instruction.admissionID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('instruction.create_date', 'desc');
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}

